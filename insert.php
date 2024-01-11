<?php

// if no errors, insert into database
if (!isset($errors)) {

    if (isset($lastBooking['total_cost'])) {
        $totalCost = $lastBooking['total_cost'];
    }

    $selectedFeatures = $_SESSION['selectedFeatures'] ?? [];

    // connect the database
    $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');

    // insert into guests
    $statement = $database->prepare('INSERT INTO guests (firstname, lastname, email) VALUES (:firstname, :lastname, :email)');
    $statement->bindParam(':firstname', $_SESSION['firstname'], PDO::PARAM_STR);
    $statement->bindParam(':lastname',  $_SESSION['lastname'], PDO::PARAM_STR);
    $statement->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
    $statement->execute();
    // get the last inserted guest_id to use in the booking table
    $lastGuestId = $database->lastInsertId();
    // insert into bookings
    $statement = $database->prepare('INSERT INTO bookings (arrival, departure, days, guest_id, hotel_id, room_id) VALUES (:arrival, :departure, :days, :guest_id, :hotel_id, :room_id)');
    $statement->bindParam(':arrival', $_SESSION['arrival'], PDO::PARAM_STR);
    $statement->bindParam(':departure', $_SESSION['departure'], PDO::PARAM_STR);
    $statement->bindParam('days',  $_SESSION['stayLength'], PDO::PARAM_INT);
    $statement->bindParam(':guest_id', $lastGuestId, PDO::PARAM_INT);
    $statement->bindParam(':hotel_id', $_SESSION['hotelId'], PDO::PARAM_INT);
    $statement->bindParam(':room_id', $_SESSION['roomId'], PDO::PARAM_INT);
    $statement->execute();

    // define the mapping from feature IDs to names
    $featureNames = [
        'cashew' => 1,
        'wine' => 2,
        'dinner' => 3,
    ];

    $selectedFeatures = array_map(function ($featureName) use ($featureNames) {
        return ['id' => $featureNames[$featureName], 'name' => $featureName];
    }, $selectedFeatures);


    // insert last guest id and features into booking_features junction table
    if (!isset($_SESSION['selectedFeatures'])) {
        $selectedFeatures = [];
    } else {
        foreach ($selectedFeatures as $feature) {
            $statement = $database->prepare('INSERT INTO booking_features (booking_id, feature_id) VALUES (:booking_id, :feature_id)');
            $statement->bindParam(':booking_id', $lastGuestId);
            $statement->bindParam(':feature_id', $feature['id']);
            $statement->execute();
        }
    }

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

    $statement = $database->prepare('SELECT features.name, features.cost FROM booking_features LEFT JOIN features ON booking_features.feature_id = features.id WHERE booking_features.booking_id = :booking_id');
    $statement->bindParam(':booking_id', $lastGuestId);
    $statement->execute();
    $features = $statement->fetchAll(PDO::FETCH_ASSOC);


    foreach ($features as $feature) {
        $featureNames[] = [
            'name' => $feature['name'],
            'cost' => $feature['cost']
        ];
    }

    // // define the total cost for the message
    // $totalCost = $_SESSION['totalCost'];

    // include the feature names in the message
    $message = "<strong>Congratulations " . $_SESSION['firstname'] . " " . $_SESSION['lastname'] . "!</strong> You have booked a  " . $_SESSION['roomType'] . " room at Harvest Haven.";

    // create object from queries
    $response = [
        'island' => $_SESSION['island'],
        'hotel' => $_SESSION['hotel'],
        'arrival_date' => $_SESSION['arrival'],
        'departure_date' => $_SESSION['departure'],
        'total_cost' => $_SESSION['totalCost'],
        'stars' => $_SESSION['stars'],
        'features' => $features ?? [],
        'additional_info' => [
            'greeting' => "Thank you for choosing Harvest Haven",
            'imageUrl' => "No image at the moment"
        ]
    ];

    $_SESSION['response'] = $response;
}
