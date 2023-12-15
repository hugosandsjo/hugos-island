<?php

// declare(strict_types=1);
declare(strict_types=1);

//require autoload guzzle
require __DIR__ . 'vendor/autoload.php';

//Start guzzle
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

// Post logic

if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['arrival'], $_POST['departure'], $_POST['roomType'])) {
                    // Trimming and sanitizing

                    $firstname = ucfirst(strtolower(htmlspecialchars(str_replace(' ', '', trim($_POST['firstname'])))));
                    $lastname = ucfirst(strtolower(htmlspecialchars(str_replace(' ', '', trim($_POST['lastname'])))));
                    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                    $arrival = $_POST['arrival'];
                    $departure = $_POST['departure'];
                    $roomType = $_POST['roomType'];


                    // Insert error messages to the $errors array if information is missing
                    if ($email === '') {
                                        $errors[] = 'The email field is empty.'  . '<br>';
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
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                        $errors[] = 'The email address is not valid.' . '<br>';
                    }
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
                                        $hotelId = (int) 1;

                                        $statement = $database->prepare('INSERT INTO bookings (arrival, departure, guest_id, hotel_id, room_id) VALUES (:arrival, :departure, :guest_id, :hotel_id, :room_id)');
                                        $statement->bindParam(':arrival', $arrival, PDO::PARAM_STR);
                                        $statement->bindParam(':departure', $departure, PDO::PARAM_STR);
                                        $statement->bindParam(':guest_id', $lastGuestId, PDO::PARAM_INT);
                                        $statement->bindParam(':hotel_id', $hotelId, PDO::PARAM_INT);
                                        $statement->bindParam(':room_id', $roomId, PDO::PARAM_INT);
                                        $statement->execute();

                                        echo "Congratulations $firstname $lastname, you have booked a room at The Florida Inn from $arrival to $departure";

                                        $statement = $database->prepare('SELECT island, "name", rating, arrival, departure
                                        FROM bookings
                                        INNER JOIN hotel
                                        ON bookings.hotel_id = hotel.id
                                        LIMIT 1;');
                                        $statement->execute();

                                        session_start();
                                        $lastBooking = $statement->fetch(PDO::FETCH_ASSOC);
                                        $_SESSION['lastBooking'] = $lastBooking;
                                        header('Location: displayjson.php');
                                        exit;
                    }
}
