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


    public function check_token_post()
    {
        $this->objOfJwt = new Jwt_Autorization();

        $token = $this->post(0);
		$result = $this->objOfJwt->DecodeToken($token);
		$user_info = $this->post(1);


		if ((!isset($user_info)) || !($result)||($result->subject->id != $user_info)) {
			$this->response(false, 200);
		}
        $this->api_response(true, 200);




    }

public function update_firebase_token_post(){

    $post = $this->post();
    $result =  $this->AuthorizationModel->update_firebase_token($post);
     $this->api_response(($result), 200);
}


}
