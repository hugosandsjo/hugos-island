<?php

// declare(strict_types=1);
declare(strict_types=1);

// post logic
if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['arrival'], $_POST['departure'], $_POST['roomType'],)) {

    // trimming and sanitizing
    $firstname = ucfirst(strtolower(htmlspecialchars(str_replace(' ', '', trim($_POST['firstname'])))));
    $lastname = ucfirst(strtolower(htmlspecialchars(str_replace(' ', '', trim($_POST['lastname'])))));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $arrival = $_POST['arrival'];
    $departure = $_POST['departure'];
    $hotelId = (int) 1; // important hotelId (is always the same since there is only one hotel)

    if (isset($_POST['features'])) {  // if any features are selected this will create an array of the chosen features
        $selectedFeatures = $_POST['features']; // this will be an array
        foreach ($selectedFeatures as $featureName) {
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
        $roomCost = (int) $roomPrices['0']['price'];
    } elseif ($roomType === 'standard') {
        $roomId = (int) 2;
        $roomCost = (int) $roomPrices['1']['price'];
    } elseif ($roomType === 'luxury') {
        $roomId = (int) 3;
        $roomCost = (int) $roomPrices['2']['price'];
    } else {
        $errors[] = 'Invalid room type selected.' . '<br>';
    }

    // calculate the total days of the booking using the arrival and departure dates and calcualting the days between
    $arrivalDate = new DateTime($arrival);
    $departureDate = new DateTime($departure);
    $interval = $arrivalDate->diff($departureDate);
    $days = $interval->format('%a') + 1; // the +1 since it only calculates the days inbetween
    $stayLength = $days;


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

    // check if date is not available
    if (!isDateAvailable($arrival, $departure, $roomId)) {
        $errors[] = "The selected dates are already booked. Please choose a different date.";
    }

    // use an associative array to map feature names to their costs
    $featurePrices = [
        'cashew' => $featureCosts['0']['cost'],
        'wine' => $featureCosts['1']['cost'],
        'dinner' => $featureCosts['2']['cost'],
    ];

    // initate feature cost
    $featuresTotal = (int) 0;

    // create arrays for features
    if (isset($_POST['features'])) {
        $selectedFeatures = $_POST['features'];
        $selectedFeatureDetails = [];
        foreach ($selectedFeatures as $featureName) {
            if (isset($featurePrices[$featureName])) {
                $featuresTotal += $featurePrices[$featureName];
                // Store both the feature ID and the feature name
                $selectedFeatureDetails[] = ['id' => $featureNames[$featureName], 'name' => $featureName];
            }
        }
    } else {
        $selectedFeatures = [];
    }

    // calculate totalcost = the basecost of the room multiplied with lenght of stay + all the feature costs
    $totalCost = $roomCost * $stayLength + $featuresTotal;

    // 30% discount if your stay is 3 days or longer
    if ($stayLength >= 3) {
        $totalCost = round($totalCost * 0.7);
    }

    // check if the errors array is empty
    if (empty($errors)) {
        $_SESSION['totalCost'] = $totalCost;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['email'] = $email;
        $_SESSION['arrival'] = $arrival;
        $_SESSION['departure'] = $departure;
        $_SESSION['stayLength'] = $stayLength;
        $_SESSION['roomType'] = $roomType;
        $_SESSION['selectedFeatures'] = $selectedFeatures;
        $_SESSION['response'] = $response;
        $_SESSION['client'] = $client;
        $_SESSION['errors'] = null;
        $_SESSION['island'] = 'Terra Verde';
        $_SESSION['hotel'] = 'Harvest Haven';
        $_SESSION['stars'] = '4';
        $_SESSION['hotelId'] = $hotelId;
        $_SESSION['roomId'] = $roomId;


        header('Location: booking.php');
        exit;
    } else {
        // empty to display the errors
        $_SESSION['errors'] = $errors;
    }
}
