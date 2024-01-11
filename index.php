<?php

declare(strict_types=1);

session_start();

// require autoload app
require __DIR__ . '/app/autoload.php';
require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/functions.php';
require __DIR__ . '/adminlogin.php';
require __DIR__ . '/prices.php';
require __DIR__ . '/calendar.php';
require __DIR__ . '/form.php';

require __DIR__ . '/header.php';
require __DIR__ . '/layout.php';
require __DIR__ . '/footer.php';
