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

     //features
     if (isset($_POST['features'])) {
          $features = $_POST['features'];
     } else {
          $features = [];
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


     // If no errors, insert into database
     if (!isset($errors)) {

          $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');  // Connect the database

          $statement = $database->prepare('INSERT INTO guests (firstname, lastname, email) VALUES (:firstname, :lastname, :email)');
          $statement->bindParam(':firstname', $firstname, PDO::PARAM_STR);
          $statement->bindParam(':lastname', $lastname, PDO::PARAM_STR);
          $statement->bindParam(':email', $email, PDO::PARAM_STR);
          $statement->execute();

          // Get the last inserted guest_id to use in the booking table
          $lastGuestId = $database->lastInsertId();
          //           $hotelId = (int) 1;

          $statement = $database->prepare('INSERT INTO bookings (arrival, departure, guest_id, hotel_id, room_id) VALUES (:arrival, :departure, :guest_id, :hotel_id, :room_id)');
          $statement->bindParam(':arrival', $arrival, PDO::PARAM_STR);
          $statement->bindParam(':departure', $departure, PDO::PARAM_STR);
          $statement->bindParam(':guest_id', $lastGuestId, PDO::PARAM_INT);
          $statement->bindParam(':hotel_id', $hotelId, PDO::PARAM_INT);
          $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
          $statement->execute();

          if (isset($_POST['features'])) {

               $features = $_POST['features'];
               $features = [];


               $statement = $database->prepare('INSERT INTO booking_features (booking_id, feature_id) VALUES (:booking_id, :feature_id)');

               foreach ($_POST['features'] as $feature) {
                    if ($feature === 'peanuts') {
                         $feature = 1;
                    }
                    if ($feature === 'vodka') {
                         $feature = 2;
                    }
                    if ($feature === 'dinner') {
                         $feature = 3;
                    }

                    $statement->bindParam(':booking_id', $lastGuestId);
                    $statement->bindParam(':feature_id', $feature);
                    $statement->execute();
               }
          }

          echo "Congratulations $firstname $lastname, you have booked a room at The Florida Inn from $arrival to $departure";

          $statement = $database->prepare('SELECT hotel.island, hotel.hotel, bookings.arrival, bookings.departure, hotel.stars, features.name, features.cost
          FROM hotel
          INNER JOIN bookings
          ON hotel.id = bookings.hotel_id
          LEFT JOIN booking_features
          ON bookings.id = booking_features.booking_id
          LEFT JOIN features
           ON booking_features.feature_id = features.id
          ORDER BY bookings.id DESC
          LIMIT 1;');

          $statement->execute();
          $lastBooking = $statement->fetch(PDO::FETCH_ASSOC);

          $response = [
               'island' => $lastBooking['island'],
               'hotel' => $lastBooking['hotel'],
               'arrival_date' => $lastBooking['arrival'],
               'departure_date' => $lastBooking['departure'],
               'total_cost' => $lastBooking['cost'],
               'stars' => $lastBooking['stars'],
               'features' => $lastBooking['name'] ? [
                    'name' => $lastBooking['name'],
                    'cost' => $lastBooking['cost']
               ] : "No features",
               'additional_info' => [
                    'greeting' => "Thank you for choosing Florda inn",
                    'imageUrl' => "No image at the moment"
               ]
          ];

          session_start();

          $_SESSION['response'] = $response;
          header('Location: displayjson.php');
          exit;
     }
}
