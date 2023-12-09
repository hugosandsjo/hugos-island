<?php
// declare strict types
declare(strict_types=1);
//require autoload
require 'vendor/autoload.php';


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


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hugos-island</title>
    <link rel="stylesheet" href="assets/style/style.css">
</head>

<body>

    <main>
        <section class="hero">
            <h1>Hugos island</h1>
            <form>
                <input type="text" name="name" placeholder="Name">
                <input type="text" name="surname" placeholder="Surname">
                <input type="text" name="email" placeholder="Email">
                <!-- <input type="date" id="start" name="stay-start" value="2024-01-01" min="2024-01-01" max="2024-01-31" /> -->
                <select name="room-type">
                    <option value="budget">Budget</option>
                    <option value="standard">Standard</option>
                    <option value="luxury">Luxury</option>
                </select>
                <?php require __DIR__ . '/calendar.php'; ?>
                <button type="submit">Send</button>
            </form>


        </section>

    </main>


</body>

</html>