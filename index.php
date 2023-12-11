<?php
// declare(strict_types=1);
declare(strict_types=1);
//require autoload guzzle
require 'vendor/autoload.php';
// require autoload app
require 'app/autoload.php';


//Start guzzle
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

// Form logic
if (isset($_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['arrival'], $_POST['departure'])) {
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

        // Get the last inserted guest_id to use in the booking table
        $lastGuestId = $database->lastInsertId();
        $hotelId = 1;

        $statement = $database->prepare('INSERT INTO bookings (arrival, departure, guest_id, hotel_id) VALUES (:arrival, :departure, :guest_id, :hotel_id)');
        $statement->bindParam(':arrival', $arrival, PDO::PARAM_STR);
        $statement->bindParam(':departure', $departure, PDO::PARAM_STR);
        $statement->bindParam(':guest_id', $lastGuestId, PDO::PARAM_INT);
        $statement->bindParam(':hotel_id', $hotelId, PDO::PARAM_INT);
        $statement->execute();


        // echo "Congratulations $firstname $lastname, you have booked a room at Hugos Island from $arrival to $departure";
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
    <header>
        <h1>Hugos island</h1>
    </header>
    <main>
        <section class="hero">

            <div class="calendar-wrapper">
                <?php require __DIR__ . '/calendar.php'; ?>
            </div>
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

                <button type="submit">Book</button>
            </form>


        </section>

    </main>


</body>

</html>