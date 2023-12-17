<?php

declare(strict_types=1);

session_start();

if (isset($_SESSION['response'])) {

    header('Content-Type: application/json');
    echo json_encode($_SESSION['response']);
}
