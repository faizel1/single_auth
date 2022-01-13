<?php


defined('BASEPATH') or exit('Direct access path is not allowed');

class User extends API
{
    public function __construct()
    {
        parent::__construct(false);
        $this->load->model('UserModel');
    }

    public function update_profile_post()
    {

        $result = $this->UserModel->update_profile($this->post());
        $this->api_response($result, 200);
    }

    public function detail_get($user_id)
    {

        $result = $this->UserModel->user_detail($user_id);
        $this->api_response($result, 200);
    }

    public function add_address_post()
    {

        $data = $this->post();
        $data['client_id'] = $this->session->userdata("user_id");

        $result = $this->UserModel->Single_save("tbl_address", $data);
        $this->api_response($result, 200);
    }

    public function detail_address_get($address_id)
    {
        $result = $this->UserModel->detail_address($address_id);
        $this->api_response($result, 200);
    }

    public function default_address_get($user_id)
    {
        $result = $this->UserModel->default_address($user_id);
        $this->api_response($result, 200);
    }

    public function profile_get($id)
    {
        $result = $this->UserModel->profile($id);

        $this->api_response($result);
    } 
}
