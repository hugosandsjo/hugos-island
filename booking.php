<?php

declare(strict_types=1);

require __DIR__ . '/app/autoload.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/header.php';
require __DIR__ . '/functions.php';
// require __DIR__ . '/adminlogin.php';
// require __DIR__ . '/prices.php';

// start guzzle
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
// new client with base uri

session_start();


if (isset($_POST['transferCode'])) {

    $client = new Client([
        'base_uri' => 'https://www.yrgopelag.se/centralbank/',
        'timeout' => 2.0,
    ]);

    $transferCode = $_POST['transferCode'];

    // Retrieve totalCost from the session
    $totalCost = $_SESSION['totalCost'];

    validateField($transferCode, 'The transfercode is missing');

    if (!isset($errors)) {

        // check valid transfercode and deposit if valid
        $validTransferCode = [
            'form_params' => [
                'transferCode' => $transferCode,
                'totalcost' => $totalCost
            ]
        ];

        try {
            $response = $client->request('POST', 'transferCode', $validTransferCode);
            $body = json_decode($response->getBody()->getContents(), true);

            if (isset($body['error']) && $body['error'] == "Not a valid GUID") {
                // the transfer code was not accepted, display an error message
                $errors[] = 'Not a valid transfer code.' . '<br>';
            } else {
                // the transfer code was accepted, deposit the totalCost and proceed with the booking
                try {
                    $deposit = [
                        'form_params' => [
                            'user' => 'hugo',
                            'transferCode' => $transferCode
                        ]
                    ];
                    $response = $client->request('POST', 'deposit', $deposit);
                    $statusCode = $response->getStatusCode();
                    $body = $response->getBody()->getContents();
                } catch (ClientException $e) {
                    echo $e->getMessage();
                }

                // if succesfull insert into database in this file
                require __DIR__ . '/insert.php';
            }
        } catch (ClientException $e) {
            $errors[] = 'Error: ' . $e->getMessage() . '<br>';
        }
    } else {

        // if not succesfull display error message
        $errors[] = 'Not a valid transfer code.' . '<br>';
    }
}

?>


<section class="lastStepSection">

    <div class="lastStep">

        <h2>Summary:</h2>

        <div class="stepDiv">
            <h5>Total cost:</h5>
            <h5> <?= $_SESSION['totalCost']; ?> </h5>
        </div>

        <div class="stepDiv">
            <h5>Arrival:</h5>
            <h5><?= $_SESSION['arrival']; ?></h5>
        </div>

        <div class="stepDiv">
            <h5>Departure:</h5>
            <h5><?= $_SESSION['departure']; ?></h5>
        </div>


        <div class="stepDiv">
            <h5>Features:</h5>
            <h5> <?php
                    // if (isset($featureNames)) {
                    //     foreach ($featureNames as $featureName) {
                    //         echo $featureName['name'] . ', ';
                    //     }
                    // } else {
                    foreach ($_SESSION['selectedFeatures'] as $feature) {
                        echo $feature . ', ';
                    }
                    // }

                    ?></h5>
        </div>

        <form action="booking.php" method="post">
            <input type="text" name="transferCode" placeholder="Transfer code">
            <button type="submit">Book</button>
            <?php if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo '<br>' . $error;
                }
            } ?>
        </form>
        <section class="succesfullBooking">
            <?php
            if (isset($message)) { ?><p>
                    <?php echo $message; ?></p>
                <form class="showJSON" action="displayjson.php" method="post" target="_blank">
                    <input class="JSONbutton" type="submit" value="Show JSON">
                </form>
            <?php } ?>
        </section>

    </div>
</section>

<?php


require __DIR__ . '/footer.php';
