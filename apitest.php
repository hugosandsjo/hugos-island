<?php

// header('Content-Type: application/json');
// $data = file_get_contents('https://www.yrgopelag.se/centralbank/');
// $islands = file_get_contents('https://www.yrgopelag.se/centralbank/islands');
// $transfercode = file_get_contents('https://www.yrgopelag.se/centralbank/transferCode');
// $withdrawal = file_get_contents('https://www.yrgopelag.se/centralbank/withdraw');
// $deposit = file_get_contents('https://www.yrgopelag.se/centralbank/deposit');

// $withdrawal = json_decode($withdrawal);

// // echo $data;
// // echo $islands;
// // echo $transfercode;
// echo $withdrawal;
// // echo $deposit;

// // var_dump($data);



// $response = $client->request('POST', 'https://www.yrgopelag.se/centralbank/', [
//     'form_params' => [
//         'startCode' => 'b9a6417f-4df0-4b5f-8b19-319b67fe8d43'
//     ]
// ]);


declare(strict_types=1);

//require autoload guzzle
require __DIR__ . '/vendor/autoload.php';

//Start guzzle
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

$client = new CLient([
  'base_uri' => 'https://www.yrgopelag.se/centralbank/',

  'timeout' => 2.0,
]);

$response = $client->request('GET', 'transferCode');
$body = (string) $response->getBody();
header('Content-Type: application/json');
echo $body;

// $payload = json_encode([
//                     "title" => "Title of island"
// ]);

// $headers = [
//                     "Content-type" => "application/json; charset=UTF-8"
// ];


// $response = $client->patch("https://www.yrgopelag.se/centralbank/islands", [
//                     "header" => $headers,
//                     "body" => $payload
// ]);


// var_dump($response->getStatusCode());

// var_dump($response->getHeader("Content-type"));

// var_dump((string) $response->getBody());
