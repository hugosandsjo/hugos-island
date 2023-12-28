<?php

declare(strict_types=1);

// session_start();

// require 'app/autoload.php';
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

$client = new Client([
                    'base_uri' => 'https://www.yrgopelag.se/centralbank/',
                    'timeout' => 2.0,
]);

// get the list of islands and print out
// $response = $client->request('GET', 'islands');
// print_r(($response->getBody()->getContents()));

//check validtransfercode
// $validTransferCode = [
//      'form_params' => [
//           'transferCode' => '7e4545a9-e962-4816-a407-d5d56501775b',
//           'totalcost' => 2
//      ]
// ];
// $response = $client->request('POST', 'transferCode', $validTransferCode);
// $body = $response->getBody()->getContents();
// echo $body;

//withdrawal pengar och få en transfercode
$withdraw = [
                    'form_params' => [
                                        'user' => 'Rune',
                                        'api_key' => '9ca1e3d1-aa16-4455-9936-739984164f40',
                                        'amount' => 3
                    ]
];
$response = $client->request('POST', 'withdraw', $withdraw);
$body = $response->getBody();
echo $body->getContents();

// use the transferCode to get money
// try {
//      $deposit = [
//           'form_params' => [
//                'user' => 'hugo',
//                'transferCode' => '7e4545a9-e962-4816-a407-d5d56501775b'
//           ]
//      ];
//      $response = $client->request('POST', 'deposit', $deposit);
//      $statusCode = $response->getStatusCode();
//      $body = $response->getBody()->getContents();
//      echo $body;
// } catch (ClientException $e) {
//      echo $e->getMessage();
// }

header('Content-Type: application/json');
// echo json_encode($withdraw);
