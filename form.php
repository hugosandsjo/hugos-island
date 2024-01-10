<?php

// declare(strict_types=1);
declare(strict_types=1);

// start guzzle
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

// new client with base uri
$client = new Client([
    'base_uri' => 'https://www.yrgopelag.se/centralbank/',
    'timeout' => 2.0,
]);

// post logic
if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['arrival'], $_POST['departure'], $_POST['roomType'], $_POST['transferCode'])) {

    // trimming and sanitizing
    $firstname = ucfirst(strtolower(htmlspecialchars(str_replace(' ', '', trim($_POST['firstname'])))));
    $lastname = ucfirst(strtolower(htmlspecialchars(str_replace(' ', '', trim($_POST['lastname'])))));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $arrival = $_POST['arrival'];
    $departure = $_POST['departure'];
    $hotelId = (int) 1; // important hotelId (is always the same since there is only one hotel)
    $transferCode = $_POST['transferCode'];
    if (isset($_POST['features'])) {  // if any features are selected this will create an array of the chosen features
        $selectedFeatures = $_POST['features']; // this will be an array
        foreach ($selectedFeatures as $featureId) {
            // process each selected feature
        }
    } else {
        $selectedFeatures = [];
    }

    // get room type
    $roomType = $_POST['roomType'];

    $roomCost = (int) 0; // initialize base cost for rooms

    // update roomtype id depending on roomtype to insert into correct calendar
    if ($roomType === 'budget') {
        $roomId = (int) 1;
        $roomCost = (int) $featureCosts[0]['cost'];
    } elseif ($roomType === 'standard') {
        $roomId = (int) 2;
        $roomCost = (int) $featureCosts[1]['cost'];
    } elseif ($roomType === 'luxury') {
        $roomId = (int) 3;
        $roomCost = (int) $featureCosts[2]['cost'];
    } else {
        $errors[] = 'Invalid room type selected.' . '<br>';
    }

    // calculate the total days of the booking using the arrival and departure dates and calcualting the days between
    $arrivalDate = new DateTime($arrival);
    $departureDate = new DateTime($departure);
    $interval = $arrivalDate->diff($departureDate);
    $days = $interval->format('%a') + 1; // the +1 since it only calculates the days inbetween
    $stayLength = $days;

    // use an associative array to map feature IDs to their costs
    // 1 = Cashews
    // 2 = Wine
    // 3 = Dinner
    $featurePrices = [
        1 => $featureCosts['0']['cost'],
        2 => $featureCosts['1']['cost'],
        3 => $featureCosts['2']['cost'],
    ];

    // initate feature cost
    $featuresTotal = (int) 0;

    foreach ($selectedFeatures as $featureId) {
        if (isset($featurePrices[$featureId])) {
            $featuresTotal += $featurePrices[$featureId];
        }
    }

    // calculate totalcost = the basecost of the room multiplied with lenght of stay + all the feature costs
    $totalCost = $roomCost * $stayLength + $featuresTotal;

    // 30% discount if your stay is 3 days or longer
    if ($stayLength >= 3) {
        $totalCost = round($totalCost * 0.7);
    }

    $_SESSION['totalCost'] = $totalCost;

    header('Location: booking.php');
    exit;

    // check if form fields are empty and display error message if thats the case
    validateField($email, 'The email field is empty');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'The email address is not valid.' . '<br>';
    }
    validateField($firstname, 'The name field is empty');
    validateField($lastname, 'The lastname field is empty');
    if ($arrival === '' || $departure === '') {
        $errors[] = 'You havent chosen your dates.' . '<br>';
    }
    //  validateField($transferCode, 'The transfercode is missing');
    // check if date is not available
    if (!isDateAvailable($arrival, $departure, $roomId)) {
        $errors[] = "The selected dates are already booked. Please choose a different date.";
    }

    // check valid transfercode and deposit if valid
    $validTransferCode = [
        'form_params' => [
            'transferCode' => $transferCode,
            'totalcost' => $totalCost
        ]
    ];

    try {
        $response = $client->request('POST', 'transferCode', $validTransferCode);
        $body = json_decode($response->getBody()->getContents(), true);

        if (isset($body['error']) && $body['error'] == "Not a valid GUID") {
            // the transfer code was not accepted, display an error message
            $errors[] = 'Not a valid transfer code.' . '<br>';
        } else {
            // the transfer code was accepted, deposit the totalCost and proceed with the booking
            try {
                $deposit = [
                    'form_params' => [
                        'user' => 'hugo',
                        'transferCode' => $transferCode
                    ]
                ];
                $response = $client->request('POST', 'deposit', $deposit);
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();
            } catch (ClientException $e) {
                echo $e->getMessage();
            }
        }
    } catch (ClientException $e) {
        $errors[] = 'Error: ' . $e->getMessage() . '<br>';
    }

    // if succesfull insert into database in this file
    require __DIR__ . '/insert.php';
}
