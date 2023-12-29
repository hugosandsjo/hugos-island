<?php

// declare(strict_types=1);
declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

$client = new Client([
                    'base_uri' => 'https://www.yrgopelag.se/centralbank/',
                    'timeout' => 2.0,
]);
//require autoload guzzle
// require __DIR__ . 'vendor/autoload.php';

// start guzzle
// use GuzzleHttp\Client;
// use GuzzleHttp\Exception\ClientException;

// post logic
if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['arrival'], $_POST['departure'], $_POST['roomType'], $_POST['transferCode'])) {

                    // Trimming and sanitizing
                    $firstname = ucfirst(strtolower(htmlspecialchars(str_replace(' ', '', trim($_POST['firstname'])))));
                    $lastname = ucfirst(strtolower(htmlspecialchars(str_replace(' ', '', trim($_POST['lastname'])))));
                    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $arrival = $_POST['arrival'];
                    $departure = $_POST['departure'];
                    $hotelId = (int) 1; // important hotelId and is always the same
                    $transferCode = $_POST['transferCode'];
                    if (isset($_POST['features'])) {  // if any features are selected this will create an array of the chosen features
                                        $selectedFeatures = $_POST['features']; // This will be an array
                                        foreach ($selectedFeatures as $featureId) {
                                                            // Process each selected feature
                                        }
                    } else {
                                        $selectedFeatures = [];
                    }
                    $roomType = $_POST['roomType'];
                    $baseCost = 0; // Initialize base cost for rooms
                    // update roomtype id depending on roomtype to insert into correct calendar
                    if ($roomType === 'budget') {
                                        $roomId = 1;
                                        $baseCost = 3;
                    } elseif ($roomType === 'standard') {
                                        $roomId = 2;
                                        $baseCost = 5;
                    } elseif ($roomType === 'luxury') {
                                        $roomId = 3;
                                        $baseCost = 10;
                    } else {
                                        $errors[] = 'Invalid room type selected.' . '<br>';
                    }

                    // calculate the total days of the booking using the arrival and departure dates and calcualting the days between
                    $arrivalDate = new DateTime($arrival);
                    $departureDate = new DateTime($departure);
                    $interval = $arrivalDate->diff($departureDate);
                    $days = $interval->format('%a') + 1; // the +1 since it only calculates the days inbetween
                    $stayLength = $days;

                    $featureCost = 0;
                    if (in_array(1, $selectedFeatures,)) {
                                        $featureCost = $featureCost + 2;
                    }
                    if (in_array(2, $selectedFeatures,)) {
                                        $featureCost = $featureCost + 3;
                    }
                    if (in_array(3, $selectedFeatures,)) {
                                        $featureCost = $featureCost + 4;
                    }
                    // calculate totalcost
                    $totalCost = $baseCost * $stayLength + $featureCost; // The basecost if the room multiplied with lenght of stay + all the feature costs

                    // 30% discount if your stay is 3 days or longer
                    if ($stayLength >= 3) {
                                        $totalCost = $totalCost * 0.7;
                                        $totalCost = round($totalCost);
                    };

                    // insert error messages to the $errors array if information is missing
                    if ($email === '') {
                                        $errors[] = 'The email field is empty.'  . '<br>';
                    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                        $errors[] = 'The email address is not valid.' . '<br>';
                    }
                    if ($firstname === '') {
                                        $errors[] = 'The name field is empty.' . '<br>';
                    }
                    if ($lastname === '') {
                                        $errors[] = 'The lastname field is empty.'  . '<br>';
                    }
                    if ($arrival === '' || $departure === '') {
                                        $errors[] = 'You havent chosen your dates.' . '<br>';
                    }
                    //      if ($transferCode === '') {
                    //           $errors[] = 'The transfercode is missing.'  . '<br>';
                    //      }

                    //check valid transfercode and deposit if valid
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
                                                            // The transfer code was not accepted, display an error message
                                                            $errors[] = 'Not a valid transfer code.' . '<br>';
                                        } else {
                                                            // The transfer code was accepted, deposit the totalCost and proceed with the booking
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
                                                                                echo $body;
                                                            } catch (ClientException $e) {
                                                                                echo $e->getMessage();
                                                            }
                                        }
                    } catch (ClientException $e) {
                                        $errors[] = 'Error: ' . $e->getMessage() . '<br>';
                    }

                    // checking availability
                    function isDateAvailable($arrival, $departure, $roomId)
                    {
                                        $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');
                                        $statement = $database->prepare(
                                                            'SELECT arrival, departure
            FROM bookings
            WHERE room_id = :roomId AND ((arrival <= :departure AND departure >= :arrival) OR (arrival >= :arrival AND departure <= :departure))'
                                        );
                                        $statement->bindParam(':roomId', $roomId, PDO::PARAM_INT);
                                        $statement->bindParam(':arrival', $arrival, PDO::PARAM_STR);
                                        $statement->bindParam(':departure', $departure, PDO::PARAM_STR);
                                        $statement->execute();
                                        $bookings = $statement->fetchAll(PDO::FETCH_ASSOC);

                                        return empty($bookings);
                    }

                    // If no errors, insert into database
                    if (!isset($errors)) {
                                        if (!isDateAvailable($arrival, $departure, $roomId)) {
                                                            $errors[] = "The selected dates are already booked. Please choose a different date.";
                                        } else {

                                                            $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');  // Connect the database

                                                            //insert into guests
                                                            $statement = $database->prepare('INSERT INTO guests (firstname, lastname, email) VALUES (:firstname, :lastname, :email)');
                                                            $statement->bindParam(':firstname', $firstname, PDO::PARAM_STR);
                                                            $statement->bindParam(':lastname', $lastname, PDO::PARAM_STR);
                                                            $statement->bindParam(':email', $email, PDO::PARAM_STR);
                                                            $statement->execute();

                                                            // Get the last inserted guest_id to use in the booking table
                                                            $lastGuestId = $database->lastInsertId();
                                                            // insert into bookings
                                                            $statement = $database->prepare('INSERT INTO bookings (arrival, departure, days, guest_id, hotel_id, room_id) VALUES (:arrival, :departure, :days, :guest_id, :hotel_id, :room_id)');
                                                            $statement->bindParam(':arrival', $arrival, PDO::PARAM_STR);
                                                            $statement->bindParam(':departure', $departure, PDO::PARAM_STR);
                                                            $statement->bindParam('days', $stayLength, PDO::PARAM_INT);
                                                            $statement->bindParam(':guest_id', $lastGuestId, PDO::PARAM_INT);
                                                            $statement->bindParam(':hotel_id', $hotelId, PDO::PARAM_INT);
                                                            $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
                                                            $statement->execute();

                                                            //insert last guest id and features into booking_features junction table
                                                            if ($selectedFeatures) {
                                                                                foreach ($selectedFeatures as $featureId) {
                                                                                                    $statement = $database->prepare('INSERT INTO booking_features (booking_id, feature_id) VALUES (:booking_id, :feature_id)');
                                                                                                    $statement->bindParam(':booking_id', $lastGuestId);
                                                                                                    $statement->bindParam(':feature_id', $featureId);
                                                                                                    $statement->execute();
                                                                                }
                                                            }

                                                            // Define the mapping from feature IDs to names
                                                            $featureNames = [
                                                                                1 => 'Peanuts',
                                                                                2 => 'Vodka',
                                                                                3 => 'Dinner',
                                                                                // Add more features as needed
                                                            ];

                                                            // Replace the IDs in $selectedFeatures with their corresponding names
                                                            $selectedFeatureNames = array_map(function ($featureId) use ($featureNames) {
                                                                                return $featureNames[$featureId];
                                                            }, $selectedFeatures);

                                                            // Convert the array of feature names to a string
                                                            $featuresString = implode(", ", $selectedFeatureNames);

                                                            // Include the feature names in the message
                                                            $message = "Congratulations $firstname $lastname! You have booked a $roomtype room at The Florida Inn from $arrival to $departure including the following features: $featuresString. <br> Your grand total is: $totalCost";

                                                            // fetch the info for the json-array and calculate the total_cost of the users stay
                                                            $statement = $database->prepare('SELECT hotel.island, hotel.hotel, bookings.id, bookings.arrival, bookings.departure, hotel.stars,
                    (SELECT COALESCE(SUM(rooms.price * bookings.days), 0) FROM bookings LEFT JOIN rooms ON rooms.id = bookings.room_id WHERE bookings.id = :booking_id) +
                    (SELECT COALESCE(SUM(features.cost), 0) FROM booking_features LEFT JOIN features ON booking_features.feature_id = features.id WHERE booking_features.booking_id = :booking_id) AS total_cost
                    FROM hotel
                    INNER JOIN bookings
                    ON hotel.id = bookings.hotel_id
                    WHERE bookings.id = :booking_id
                    ORDER BY bookings.id DESC
                    LIMIT 1');

                                                            $statement->bindParam(':booking_id', $lastGuestId);
                                                            $statement->execute();
                                                            $lastBooking = $statement->fetch(PDO::FETCH_ASSOC);

                                                            // Fetch the features for the last booking in its own query
                                                            $statement = $database->prepare('SELECT features.id, features.name, features.cost FROM booking_features LEFT JOIN features ON booking_features.feature_id = features.id WHERE booking_features.booking_id = :booking_id');
                                                            $statement->bindParam(':booking_id', $lastBooking['id']);
                                                            $statement->execute();
                                                            $features = $statement->fetchAll(PDO::FETCH_ASSOC);

                                                            // Create an associative array where the keys are the feature IDs and the values are the feature names
                                                            $featureNames = [];
                                                            foreach ($features as $feature) {
                                                                                $featureNames[] = [
                                                                                                    'name' => $feature['name'],
                                                                                                    'cost' => $feature['cost']
                                                                                ];
                                                            }
                                                            // make the 30% discount to also be applied to the json-file
                                                            if ($stayLength >= 3) {
                                                                                $lastBooking['total_cost'] = round($lastBooking['total_cost'] * 0.7);
                                                            }

                                                            // Create object from queries
                                                            $response = [
                                                                                'island' => $lastBooking['island'],
                                                                                'hotel' => $lastBooking['hotel'],
                                                                                'arrival_date' => $lastBooking['arrival'],
                                                                                'departure_date' => $lastBooking['departure'],
                                                                                'total_cost' => $lastBooking['total_cost'],
                                                                                'stars' => $lastBooking['stars'],
                                                                                // Select from features with its own query
                                                                                'features' => $featureNames ?: [],
                                                                                'additional_info' => [
                                                                                                    'greeting' => "Thank you for choosing Florida inn",
                                                                                                    'imageUrl' => "No image at the moment"
                                                                                ]
                                                            ];

                                                            print_r($response);
                                                            print_r($totalCost);
                                                            print_r($selectedFeatures);

                                                            $_SESSION['response'] = $response;
                                        }
                    }
}
