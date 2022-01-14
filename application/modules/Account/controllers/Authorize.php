<?php


defined('BASEPATH') or exit('Direct access path is not allowed');



class Authorize extends API
{
    public function __construct()
    {
        parent::__construct(false);
        $this->load->model("AuthorizationModel");
    }


    public function index_post()
    {

        $post = $this->post();
        $result =  $this->AuthorizationModel->authorize($post);
         $this->api_response(($result), 200);
    }

}
