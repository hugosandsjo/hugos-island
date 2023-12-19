<?php

// declare(strict_types=1);
declare(strict_types=1);

//require autoload guzzle
// require __DIR__ . 'vendor/autoload.php';

// start guzzle
// use GuzzleHttp\Client;
// use GuzzleHttp\Exception\ClientException;

// post logic
if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['arrival'], $_POST['departure'], $_POST['roomType'])) {

     // Trimming and sanitizing
     $firstname = ucfirst(strtolower(htmlspecialchars(str_replace(' ', '', trim($_POST['firstname'])))));
     $lastname = ucfirst(strtolower(htmlspecialchars(str_replace(' ', '', trim($_POST['lastname'])))));
     $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
     $arrival = $_POST['arrival'];
     $departure = $_POST['departure'];
     $roomType = $_POST['roomType'];
     $selectedFeatures = $_POST['features']; // Assuming 'features' is the name of your checkboxes
     // important hotelId
     $hotelId = (int) 1;
     // if any features are selected this will create an array of the chosen features
     if (isset($_POST['features'])) {
          $selectedFeatures = $_POST['features']; // This will be an array
          foreach ($selectedFeatures as $featureId) {
               // Process each selected feature
          }
     }

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

     //      print_r($selectedFeatures);

     // If no errors, insert into database
     if (!isset($errors)) {

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
          $statement = $database->prepare('INSERT INTO bookings (arrival, departure, guest_id, hotel_id, room_id) VALUES (:arrival, :departure, :guest_id, :hotel_id, :room_id)');
          $statement->bindParam(':arrival', $arrival, PDO::PARAM_STR);
          $statement->bindParam(':departure', $departure, PDO::PARAM_STR);
          $statement->bindParam(':guest_id', $lastGuestId, PDO::PARAM_INT);
          $statement->bindParam(':hotel_id', $hotelId, PDO::PARAM_INT);
          $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
          $statement->execute();

          //insert last guest id and features into booking_features junction table
          foreach ($selectedFeatures as $featureId) {
               $statement = $database->prepare('INSERT INTO booking_features (booking_id, feature_id) VALUES (:booking_id, :feature_id)');
               $statement->bindParam(':booking_id', $lastGuestId);
               $statement->bindParam(':feature_id', $featureId);
               $statement->execute();
          }

          echo "Congratulations $firstname $lastname, you have booked a room at The Florida Inn from $arrival to $departure";

          // fetch the info for the json-array
          $statement = $database->prepare('SELECT hotel.island, hotel.hotel, bookings.id, bookings.arrival, bookings.departure, hotel.stars,
(SELECT COALESCE(SUM(rooms.price), 0) FROM booking_rooms LEFT JOIN rooms ON booking_rooms.room_id = rooms.id WHERE booking_rooms.booking_id = bookings.id) +
(SELECT COALESCE(SUM(features.cost), 0) FROM booking_features LEFT JOIN features ON booking_features.feature_id = features.id WHERE booking_features.booking_id = bookings.id) AS total_cost
FROM hotel
INNER JOIN bookings
ON hotel.id = bookings.hotel_id
ORDER BY bookings.id DESC
LIMIT 1');
          $statement->execute();
          $lastBooking = $statement->fetch(PDO::FETCH_ASSOC);

          //           print_r($lastBooking);

          // Fetch the features for the last booking in its own query
          $statement = $database->prepare('SELECT features.id, features.name, features.cost FROM booking_features LEFT JOIN features ON booking_features.feature_id = features.id WHERE booking_features.booking_id = :booking_id');
          $statement->bindParam(':booking_id', $lastBooking['id']);
          $statement->execute();
          $features = $statement->fetchAll(PDO::FETCH_ASSOC);

          // Create an associative array where the keys are the feature IDs and the values are the feature names
          //           print_r($features);

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

          print_r($response);

          session_start();

          $_SESSION['response'] = $response;
          header('Location: displayjson.php');
          exit;
     }
}
