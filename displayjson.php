<?php

declare(strict_types=1);

// start the session
session_start();

// retrieve the $response array from the session
$response = $_SESSION['response'] ?? null;

// convert the $response array to JSON and print it
if ($response !== null) {
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}
