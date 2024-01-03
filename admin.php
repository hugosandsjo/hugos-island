<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form>
        <input type="text" name="username" placeholder="username">
        <input type="password" name="password" placeholder="password">
    </form>

</body>

</html>

<?php
$user = [
    'name' => 'hugo',
    'password' => '1234'
];

if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] === $user['name'] && $_POST['password'] === $user['password']) {
        echo 'Welcome ' . $user['name'];
    } else {
        echo 'Wrong username or password';
    }
}
?>