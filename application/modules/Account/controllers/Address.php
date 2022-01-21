<?php


defined('BASEPATH') or exit('Direct access path is not allowed');

class Address extends API
{
    public function __construct()
    {
        parent::__construct(false);
        $this->load->model('AddressModel');
    }

    public function add_address_post()
    {

        $data = $this->post();
        $result = $this->AddressModel->Single_save("tbl_address", $data);
        $this->api_response($result, 200);
    }

    public function detail_address_get($address_id)
    {
        $result = $this->AddressModel->detail_address($address_id);
        $this->api_response($result, 200);
    }

    public function default_address_get($user_id)
    {
        $result = $this->AddressModel->default_address($user_id);
        $this->api_response($result, 200);
    }


    public function list_get($id)
    {
        $result = $this->AddressModel->list($id);
        $this->api_response($result, 200);
    }
}
