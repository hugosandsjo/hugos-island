<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$user = [
     'username' => $_ENV['USER_NAME'],
     'password' => $_ENV['API_KEY']
];

if (isset($_POST['username']) && isset($_POST['password'])) {
     $username = htmlspecialchars($_POST['username']);
     $password = htmlspecialchars($_POST['password']);
     if ($_POST['username'] === $user['username'] && $_POST['password'] === $user['password']) {
          header('Location: admin.php');
          exit;
     } else {
          echo 'Wrong username or password';
     }
}
