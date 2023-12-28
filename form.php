<?php

// declare(strict_types=1);
declare(strict_types=1);

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
     if (isset($_POST['features'])) {  // if any features are selected this will create an array of the chosen features
          $selectedFeatures = $_POST['features']; // This will be an array
          foreach ($selectedFeatures as $featureId) {
               // Process each selected feature
          }
     } else {
          $selectedFeatures = [];
     }
     $roomType = $_POST['roomType'];
     // Depending on roomtype
     if ($roomType === 'budget') {
          $roomId = 1;
     } elseif ($roomType === 'standard') {
          $roomId = 2;
     } elseif ($roomType === 'luxury') {
          $roomId = 3;
     } else {
          $errors[] = 'Invalid room type selected.' . '<br>';
     }
     $transferCode = $_POST['transferCode'];


     // calculate the total days of the booking using the arrival and departure dates and calcualting the days between
     $arrivalDate = new DateTime($arrival);
     $departureDate = new DateTime($departure);
     $interval = $arrivalDate->diff($departureDate);
     $days = $interval->format('%a') + 1; // the +1 since it only calculates the days inbetween
     $stayLength = $days;

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

               $message = "Congratulations $firstname $lastname, you have booked a room at The Florida Inn from $arrival to $departure";

               // fetch the info for the json-array and calculate the total_cost of the users stay
               $statement = $database->prepare('SELECT hotel.island, hotel.hotel, bookings.id, bookings.arrival, bookings.departure, hotel.stars,
            (SELECT COALESCE(SUM(rooms.price * bookings.days), 0) FROM bookings LEFT JOIN rooms ON bookings.room_id = rooms.id WHERE rooms.id = bookings.id) +
            (SELECT COALESCE(SUM(features.cost), 0) FROM booking_features LEFT JOIN features ON booking_features.feature_id = features.id WHERE booking_features.booking_id = bookings.id) AS total_cost
            FROM hotel
            INNER JOIN bookings
            ON hotel.id = bookings.hotel_id
            ORDER BY bookings.id DESC
            LIMIT 1');
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

               //                print_r($response);

               $_SESSION['response'] = $response;
          }
     }
}
