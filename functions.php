<?php

declare(strict_types=1);

// form functions

// insert error messages to the $errors array if information is missing
function validateField(string $field, string $message): void // function to see if field is empty
{
    global $errors;
    if ($field === '') {
        $errors[] = $message . '<br>';
    }
}

//calendar functions

// function checking availability
function isDateAvailable(string $arrival, string $departure, int $roomId): bool
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

// function to add a booking to the events array and get displayed on the calender depending on the room type
function addBookingsToEvents(int $roomId): array
{
    // connect to the database
    $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');
    $statement = $database->prepare(
        'SELECT arrival, departure
     FROM bookings
     WHERE room_id = :roomId'
    );
    $statement->bindParam(':roomId', $roomId, PDO::PARAM_INT);
    $statement->execute();
    $bookings = $statement->fetchAll(PDO::FETCH_ASSOC);

    // initialize the occupied array
    $occupied = [];

    // initialize the events array for all room categorys
    $eventsBudget = [];
    $eventsStandard = [];
    $eventsLuxury = [];

    // variable to store the error message
    $errorMessage = null;

    // add the dates of arrival and departure and the dates inbetween into the temporary occupied array
    foreach ($bookings as $booking) {
        $start = $booking['arrival'];
        $end = $booking['departure'];
        $endPlusOne = date('Y-m-d', strtotime($end . ' +1 day')); // add one day to the end date
        $interval = new DateInterval('P1D');
        $period = new DatePeriod(new DateTime($start), $interval, new DateTime($endPlusOne));

        $tempOccupied = []; // temporary array to hold the dates for this booking
        $bookingConflict = false; // flag to track if there's a booking conflict

        foreach ($period as $date) {

            $bookedDate = $date->format('Y-m-d');

            if (!in_array($bookedDate, $occupied)) {
                $tempOccupied[] = $bookedDate; // add the date to the temporary array
            } else {
                $bookingConflict = true; // set the flag to true
                break;
            }
        }

        // if the booking was successful, add the dates from the temporary array to the occupied array and depending on the roomId add it to the matching calendar (budget, standard or luxury)
        if (!$bookingConflict) {
            $occupied = array_merge($occupied, $tempOccupied);

            if ($roomId === 1) {
                $eventsBudget[] = [
                    'start' => $start,
                    'end' => $end,
                    'summary' => '',
                    'mask' => true,
                    'classes' => ['booked']
                ];
            }

            if ($roomId === 2) {
                $eventsStandard[] = [
                    'start' => $start,
                    'end' => $end,
                    'summary' => '',
                    'mask' => true,
                    'classes' => ['booked']
                ];
            }

            if ($roomId === 3) {
                $eventsLuxury[] = [
                    'start' => $start,
                    'end' => $end,
                    'summary' => '',
                    'mask' => true,
                    'classes' => ['booked']
                ];
            }
        }
    }

    if ($errorMessage) {
        echo $errorMessage; // echo the error message if it is set
    }

    return [
        'eventsBudget' => $eventsBudget,
        'eventsStandard' => $eventsStandard,
        'eventsLuxury' => $eventsLuxury
    ];
}
