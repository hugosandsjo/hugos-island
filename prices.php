<?php

// to get the prices for the rooms and make them updateable in admin.php
$database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');
$query = $database->prepare("SELECT room_class, price FROM rooms");
$query->execute();
$roomPrices = $query->fetchAll(PDO::FETCH_ASSOC);
// and the same for feature prices
$query = $database->prepare("SELECT id, cost FROM features");
$query->execute();
$featureCosts = $query->fetchAll(PDO::FETCH_ASSOC);
