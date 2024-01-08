<?php

// if no errors, insert into database
if (!isset($errors)) {

    // connect the database
    $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');

    // insert into guests
    $statement = $database->prepare('INSERT INTO guests (firstname, lastname, email) VALUES (:firstname, :lastname, :email)');
    $statement->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $statement->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    // get the last inserted guest_id to use in the booking table
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

    // define the mapping from feature IDs to names
    $featureNames = [
        1 => 'Cashews',
        2 => 'Vodka',
        3 => 'Dinner',
    ];

    // replace the IDs in $selectedFeatures with their corresponding names
    $selectedFeatureNames = array_map(function ($featureId) use ($featureNames) {
        return $featureNames[$featureId];
    }, $selectedFeatures);

    // convert the array of feature names to a string
    $featuresString = implode(", ", $selectedFeatureNames);

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

    // fetch cost for features to display on site
    $query = $database->prepare("SELECT id, cost FROM features");
    $query->execute();
    $featurePrices = $query->fetchAll(PDO::FETCH_ASSOC);

    // create an associative array where the keys are the feature IDs and the values are the feature names
    $featureNames = [];
    foreach ($features as $feature) {
        $featureNames[] = [
            'name' => $feature['name'],
            'cost' => $feature['cost']
        ];
    }
    // make the 30% discount also get applied to the json-file
    if ($stayLength >= 3) {
        $lastBooking['total_cost'] = round($lastBooking['total_cost'] * 0.7);
    }

    // define the total cost for the message
    $totalCost = $lastBooking['total_cost'];

    // include the feature names in the message
    $message = "Congratulations $firstname $lastname! You have booked a $roomType room at Harvest Haven from $arrival to $departure including the following features: <br> $featuresString <br> Your grand total is: $totalCost";

    // create object from queries
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
            'greeting' => "Thank you for choosing Harvest Haven",
            'imageUrl' => "No image at the moment"
        ]
    ];

    $_SESSION['response'] = $response;
}
