<?php

use GuzzleHttp\Psr7\Query;

// to get the prices for the rooms and make them updateable in admin.php
$database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');
$query = $database->prepare("SELECT room_class, price FROM rooms");
$query->execute();
$roomPrices = $query->fetchAll(PDO::FETCH_ASSOC);
// and the same for feature prices
$query = $database->prepare("SELECT id, cost FROM features");
$query->execute();
$featureCosts = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Hugos-island</title>
     <link rel="stylesheet" href="assets/style/style.css">
     <script src="assets/scripts/script.js" defer></script>
</head>

<body>