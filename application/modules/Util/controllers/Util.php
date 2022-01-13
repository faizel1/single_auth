<?php

defined('BASEPATH') or exit('Direct access path is not allowed');


class Util extends API
{
    public $util;

    public function __construct()
    {
        parent::__construct(false);
        $this->load->model("UtilModel");
    }

    public function content_get($content_string)
    {
        $result = $this->UtilModel->content($content_string);
        $this->api_response($result);
    }

    public function contact_info_get() /* footer contact info & contact page info */
    {

        $result = $this->UtilModel->contact_us();
        $this->api_response($result);
    }

    public function contact_us_post()
    {

        $post = $this->post();

        $result = $this->UtilModel->Single_Save("tbl_feedback", $post);
        $this->api_response($result);
    }


    public function lookup_post()
    {
        $result = $this->UtilModel->lookup($this->post());
        $this->api_response($result);
    }





}
