<?php

require 'vendor/autoload.php';

use benhall14\phpCalendar\Calendar as Calendar;

// Create a new calendar
$calendar = new Calendar();

// function to add a booking to the events array and get displayed on the calender 
function addBookingsToEvents()
{
    // Connect to the database
    $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');
    $statement = $database->prepare('SELECT arrival, departure FROM bookings');
    $statement->execute();
    $bookings = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Starting occupied
    $occupied = [];
    // Initialize the events array
    $events = [];

    // Check to see if 
    foreach ($bookings as $booking) {
        $start = $booking['arrival'];
        $end = $booking['departure'];
        $end = date('Y-m-d', strtotime($end . ' +1 day')); // Add one day to the end date
        $interval = new DateInterval('P1D');
        $period = new DatePeriod(new DateTime($start), $interval, new DateTime($end));
        foreach ($period as $date) {
            $bookedDate = $date->format('Y-m-d');

            if (!in_array($bookedDate, $occupied)) {
                $occupied[] = $bookedDate;
                $events[] = [
                    'start' => $start,
                    'end' => $end,
                    'summary' => '',
                    'mask' => true,
                    'classes' => ['booked']
                ];
            } else {
                echo "Sorry already booked";
                break 2;
            }
        }
    }

    // Just a check to see the values in the $occupied-array
    foreach ($occupied as $date) {
        echo $date . '<br>';
    }

    return $events;
}

// Use the function
$events = addBookingsToEvents();

// Add the events to the calendar
$calendar->addEvents($events);

// Defines what month to display
$calendar->display(date('2024-01-01'));
