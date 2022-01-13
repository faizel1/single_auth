<?php


defined('BASEPATH') or exit('Direct access path is not allowed');



class Application extends API
{
    public function __construct()
    {
        parent::__construct(false);
        $this->load->model("ApplicationModel");

    }

    public function consumer_post()
    {
        $post=$this->post();
        $post['password'] = password_hash($post['password'], PASSWORD_BCRYPT);

        $result = $this->ApplicationModel->Single_Save("tbl_account",$post);
        $this->api_response($result, 200);
    }

    public function supplier_post()
    {
        $result = $this->ApplicationModel->save_supplier($this->post());
        $this->api_response($result, 200);
    }

    public function driver_post()
    {
        $result = $this->ApplicationModel->save_driver($this->post());
        $this->api_response($result, 200);
    }

   
}