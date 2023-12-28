<?php

declare(strict_types=1);

// Start the session
session_start();

// Retrieve the $response array from the session
$response = $_SESSION['response'] ?? null;

// Convert the $response array to JSON and print it
if ($response !== null) {
                    header('Content-Type: application/json');
                    echo json_encode($response, JSON_PRETTY_PRINT);
}
