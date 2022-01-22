<?php

use Restserver\Libraries\REST;

class Lookup extends API
{
    public function __construct()
    {
        parent::__construct(false);

        $this->load->model("LookupModel");
    }

    public function index_post()
	{
		$result = $this->LookupModel->Saves($this->post());
		$this->api_response($result, $result["statusCode"]);
	}

    public function index_get( $project_type,$limit)
    {
        $result = $this->LookupModel->List($limit, $project_type);
        $this->api_response($result, $result["statusCode"]);
    }

    public function detail_get( $project_type,$id)
    {
        $result = $this->LookupModel->Detail("tbl_lookup", $id, $project_type);
        $this->api_response($result, $result["statusCode"]);
    }


    public function load_lookup_key_get()
    {
        $result = $this->LookupModel->load_category();
        $this->api_response($result, $result["statusCode"]);
    }

    public function load_lookup_post()
    {
        $result = $this->LookupModel->load_parent($this->post());
        $this->api_response($result, $result["statusCode"]);
    }

    public function filter_post()
    {
        $result = $this->LookupModel->filter_lookup($this->post());
        $this->api_response($result, $result["statusCode"]);
    }

    //filter data by searching,filtering,sorting & paging ...
    public function search_post()
    {
        $post = $this->post();

        $post["where_col"] = ["parent", "category", "value"];

        $this->process->pre_process_query($post);

        $result = $this->LookupModel->List($post["limit"]);
        $this->api_response($result, $result["statusCode"]);
    }
}
