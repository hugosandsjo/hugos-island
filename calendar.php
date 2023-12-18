<?php

require 'vendor/autoload.php';

use benhall14\phpCalendar\Calendar as Calendar;

// Create a new calendar
$calendarBudget = new Calendar();
$calendarStandard = new Calendar();
$calendarLuxury = new Calendar();

// function to add a booking to the events array and get displayed on the calender depending on the roomtype
function addBookingsToEvents($roomId)
{
     // Connect to the database
     $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');
     $statement = $database->prepare(
          'SELECT arrival, departure
     FROM bookings
     WHERE room_id = :roomId'
     );
     $statement->bindParam(':roomId', $roomId, PDO::PARAM_INT);
     $statement->execute();
     $bookings = $statement->fetchAll(PDO::FETCH_ASSOC);

     $occupied = [];  // Initialize the occupied array
     $eventsBudget = [];  // Initialize the events array
     $eventsStandard = [];  // Initialize the events array
     $eventsLuxury = [];  // Initialize the events array
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
                    $errorMessage = "Sorry already booked <br>"; // Store the error message in the variable
                    $bookingConflict = true; // Set the flag to true
                    break;
               }
          }
          // If the booking was successful, add the dates from the temporary array to the occupied array
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
          echo $errorMessage; // Echo the error message if it is set
     }

     // Just a check to see the values in the $occupied-array
     foreach ($occupied as $date) {
          echo $date . '<br>';
     }

     return [
          'eventsBudget' => $eventsBudget,
          'eventsStandard' => $eventsStandard,
          'eventsLuxury' => $eventsLuxury
     ];
}
