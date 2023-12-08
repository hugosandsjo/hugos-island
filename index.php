<?php

declare(strict_types=1);
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

new Client(['base_uri' => 'https://www.yrgopelag.se/centralbank/']);

$response = $client->request('POST', 'https://www.yrgopelag.se/centralbank/', [
    'form_params' => [
        'startCode' => 'b9a6417f-4df0-4b5f-8b19-319b67fe8d43'
    ]
]);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo '<pre>';
echo $_ENV['API_KEY'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hugos-island</title>
    <link rel="stylesheet" href="assets/style/style.css">

<body>
    <main>
        <section class="hero">
            <h1>Hugos island</h1>
            <form>
                <input type="text" name="name" placeholder="Name">
                <input type="text" name="email" placeholder="Email">
                <input type="text" name="subject" placeholder="Subject">
                <textarea name="message" placeholder="Message"></textarea>
                <button type="submit">Send</button>
            </form>
        </section>

    </main>


</body>

</html>