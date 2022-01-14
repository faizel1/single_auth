<?php


defined('BASEPATH') or exit('Direct access path is not allowed');

require_once "vendor/autoload.php";

use GuzzleHttp\Client;

class Authentication extends API
{
public $commerce_public_url="http://localhost/EcommercePublicAPI";


    public function __construct()
    {
        parent::__construct(false);
        $this->load->model("AuthenticationModel");
        $this->load->library('logger');
    }

    public function change_password_post()
    {
        $result = $this->AuthenticationModel->change_password($this->post());
        $this->api_response($result, 200);
    }

    public function check_pin_post()
    {


        $result = $this->AuthenticationModel->check_pin($this->post());

        if ($result['status']) {
            $data = array( 'id' => $result['message']->id);

            $client = new Client(['base_uri' => $this->commerce_public_url ,]);

            $response = $client->request('POST', $this->commerce_public_url."/Account/Authentication/generate_token", [
                'body' => json_encode($data),
                'timeout' => 30.0, 
                
                // set higher if timeout still happens
                
                'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json'],

            ]);

            $result['message']->token = json_decode($response->getBody()->read(1024));
        }

        $this->api_response($result, 200);
    }

    public function find_post()
    {
        $result = $this->AuthenticationModel->find_account($this->post());
        $this->api_response($result, 200);
    }

    public function login_post()
    {

        $result = $this->AuthenticationModel->login($this->post());

        if ($result['status']) {
            $data = array( 'id' => $result['message']->id);

            $client = new Client(['base_uri' => $this->commerce_public_url ,]);

            $response = $client->request('POST', $this->commerce_public_url."/Account/Authentication/generate_token", [
                'body' => json_encode($data),
                'timeout' => 30.0, // set higher if timeout still happens
                'headers'  => ['content-type' => 'application/json', 'Accept' => 'application/json'],

            ]);

            $result['message']->token = json_decode($response->getBody()->read(1024));
        }

        $this->api_response($result, 200);
    }

    public function recover_password_post()
    {
        parent::__construct(true);

        $result = $this->AuthenticationModel->recover_password($this->post());
        $this->api_response($result, 200);
    }

    public function logout_get()
    {
        $result = $this->session->unset_userdata("user_info");
        $this->api_response($result, 200);
    }
}
