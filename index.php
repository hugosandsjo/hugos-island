<?php
// declare(strict_types=1);
declare(strict_types=1);
//require autoload guzzle
require 'vendor/autoload.php';
// require autoload app
require 'app/autoload.php';


//guzzle
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


new Client(['base_uri' => 'https://www.yrgopelag.se/centralbank/']);

// $response = $client->request('POST', 'https://www.yrgopelag.se/centralbank/', [
//     'form_params' => [
//         'startCode' => 'b9a6417f-4df0-4b5f-8b19-319b67fe8d43'
//     ]
// ]);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// echo '<pre>';
// echo $_ENV['API_KEY'];

// header('Content-Type: application/json');


// $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');
// $statement = $database->prepare('SELECT * FROM rooms');
// $statement->execute();
// while ($row = $statement->fetchObject()) {
//     echo $row->room_class . "<br>";
// }

// Form logic
if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'])) {
    // Trimming and sanitizing
    $firstname = htmlspecialchars(str_replace(' ', '', trim($_POST['firstname'])));
    $lastname = htmlspecialchars(str_replace(' ', '', trim($_POST['lastname'])));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $arrival = $_POST['arrival'];
    $departure = $_POST['departure'];

    if ($email === '') {
        $errors[] = 'The email field is missing.';
    } else {
        $database = new PDO('sqlite:' . __DIR__ . '/app/database/database.db');
        $statement = $database->prepare('INSERT INTO guests (firstname, lastname, email) VALUES (:firstname, :lastname, :email)');
        $statement->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $statement->bindParam(':lastname', $lastname, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        echo $firstname . '<br>';
        echo $lastname . '<br>';
        echo $email . '<br>';
    }
}

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

    <main>
        <section class="hero">
            <h1>Hugos island</h1>
            <form action="index.php" method="post">
                <input type="text" name="firstname" placeholder="Name">
                <input type="text" name="lastname" placeholder="Lastname">
                <input type="text" name="email" placeholder="Email">
                <select name="room-type">
                    <option value="budget">Budget</option>
                    <option value="standard">Standard</option>
                    <option value="luxury">Luxury</option>
                </select>
                <input type="text" id="arrival" name="arrival" placeholder="Arrival">
                <input type="text" id="departure" name="departure" placeholder="Departure">

                <?php require __DIR__ . '/calendar.php'; ?>
                <button type="submit">Send</button>
            </form>


        </section>

    </main>


</body>

</html>