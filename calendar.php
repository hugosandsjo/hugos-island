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

    $occupied = [];  // Initialize the occupied array
    $events = [];  // Initialize the events array
    $errorMessage = null; // Variable to store the error message

    // Check to see if 
    $errorMessage = null; // Variable to store the error message

    foreach ($bookings as $booking) {
        $start = $booking['arrival'];
        $end = $booking['departure'];
        $endPlusOne = date('Y-m-d', strtotime($end . ' +1 day')); // Add one day to the end date
        $interval = new DateInterval('P1D');
        $period = new DatePeriod(new DateTime($start), $interval, new DateTime($endPlusOne));

        $tempOccupied = []; // Temporary array to hold the dates for this booking
        $bookingConflict = false; // Flag to track if there's a booking conflict

        foreach ($period as $date) {
            $bookedDate = $date->format('Y-m-d');

            if (!in_array($bookedDate, $occupied)) {
                $tempOccupied[] = $bookedDate; // Add the date to the temporary array
            } else {
                $errorMessage = "Sorry already booked"; // Store the error message in the variable
                $bookingConflict = true; // Set the flag to true
                break;
            }
        }

        // If the booking was successful, add the dates from the temporary array to the occupied array
        if (!$bookingConflict) {
            $occupied = array_merge($occupied, $tempOccupied);

            $events[] = [
                'start' => $start,
                'end' => $end, // Use the original end date here
                'summary' => '',
                'mask' => true,
                'classes' => ['booked']
            ];
        }
    }

    if ($errorMessage) {
        echo $errorMessage; // Echo the error message if it is set
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
