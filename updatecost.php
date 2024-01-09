<?php

// Retrieve the data sent via AJAX
$roomType = $_POST['roomType'];
$features = explode(',', $_POST['features']);
$arrival = $_POST['arrival'];
$departure = $_POST['departure'];

// Perform the calculations...
// ...

// Return the total cost
echo $totalCost;
