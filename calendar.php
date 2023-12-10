<?php

require 'vendor/autoload.php';

use benhall14\phpCalendar\Calendar as Calendar;

// Create a new calendar
$calendar = new Calendar();

// $events = array();

// $events[] = array(
//     'start' => '2024-01-14',
//     'end' => '2024-01-18',
//     'summary' => '',
//     'mask' => true,
//     'classes' => ['booked']
// );

function addBookingsToEvents()
{
    // Connect to the database
    $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');
    $statement = $database->prepare('SELECT arrival, departure FROM booking');
    $statement->execute();
    $bookings = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Initialize the events array
    $events = [];

    // Add each booking to the events array
    foreach ($bookings as $booking) {
        $events[] = [
            'start' => $booking['arrival'],
            'end' => $booking['departure'],
            'summary' => '',
            'mask' => true,
            'classes' => ['booked']
        ];
    }

    return $events;
}

$dates = [
    '2024-01-01',
    '2024-01-02',
    '2024-01-03',
    '2024-01-04',
    '2024-01-05',
    '2024-01-06',
    '2024-01-07',
    '2024-01-08',
    '2024-01-09',
    '2024-01-10',
    '2024-01-11',
    '2024-01-12',
    '2024-01-13',
    '2024-01-14',
    '2024-01-15',
    '2024-01-16',
    '2024-01-17',
    '2024-01-18',
    '2024-01-19',
    '2024-01-20',
    '2024-01-21',
    '2024-01-22',
    '2024-01-23',
    '2024-01-24',
    '2024-01-25',
    '2024-01-26',
    '2024-01-27',
    '2024-01-28',
    '2024-01-29',
    '2024-01-30',
    '2024-01-31',
];

// Use the function
$events = addBookingsToEvents();

print_r($events);

// Add the events to the calendar
$calendar->addEvents($events);

// Defines what month to display
$calendar->display(date('2024-01-01'));
