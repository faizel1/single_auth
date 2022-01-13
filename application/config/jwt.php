<?php
defined("BASEPATH") or exit("No direct script access allowed");

$config['auth']['jwt'] = [
    'key' => 'easy_commerce_public_api', // your custom key
    'algorithm' => 'HS256',
    'token_lifetime' => 86500*365, // in seconds which means it is a year
    'refresh_token_lifetime' => 86500 * 7,


// in seconds, expired in 7 days ahead compare to access_token

];
