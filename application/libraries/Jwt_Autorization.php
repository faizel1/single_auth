<?php

require FCPATH.'vendor/autoload.php';
use \Firebase\JWT\JWT;


class Jwt_Autorization
{


    /*************This function Generate token using the given string  **************/


    public function GenerateToken($user_id)
    {

        $CI = &get_instance();
        $CI->config->load('jwt');

        $token_lifetime = $CI->config->item('auth')['jwt']['token_lifetime'];
        $key = $CI->config->item('auth')['jwt']['key'];
        $algorithm = $CI->config->item('auth')['jwt']['algorithm'];


        $date = new DateTime();


        $payload = [
            'iss' => "smart_e-commerce" . ' - Issuer',
            'subject' => [
                'id' => $user_id,
                'name' => "smart_e-commerce"
            ],
            'exp' => $date->getTimeStamp() + $token_lifetime,
            'iat' => $date->getTimeStamp()
        ];

        return JWT::encode($payload, $key, $algorithm);




    }

    /*************This function DecodeToken token **************/

    public function DecodeToken($token)
    {
        $CI = &get_instance();
        $CI->config->load('jwt');

        $key = $CI->config->item('auth')['jwt']['key'];

        try {
            return JWT::decode($token, $key, array('HS256'));

        } catch (Exception $e) {
            return false;
        }

    }
}
