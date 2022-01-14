<?php

require FCPATH.'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;
Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
header("HTTP/1.1 200 OK");

class API extends RestController {

	public function __construct($Auth = true)
	{

		 parent::__construct();

		$this->load->library("logger");
		
		if ($Auth) {
			$this->validate_user();
		}

		$valid = $this->validate_forms();
		if (!$valid['status']) {
			$this->response($valid, 200);
		}
	}

	public function api_response($result, $status=200)
	{
		$this->response($result, $status);
	}


	// check if the email or phone_number or plate_number is already exist in the database or not

	public function is_exist($value, $field)
	{
		$result = 0;
		$id = null;
		$ci = &get_instance();
		isset($_POST["id"]) ? ($id = $_POST["id"]) : null;

		if ($field == "email_address") {
			!is_null($id) ? $ci->db->where("id !=", $id) : null;
			$ci->db->where([$field => $value]);

			$result = $ci->db->count_all_results("tbl_address");
		}

		if ($field == "phone_number") {
			!is_null($id) ? $ci->db->where("id !=", $id) : null;
			$ci->db->where([$field => $value]);

			$result = $ci->db->count_all_results("tbl_address");
		}

		if ($result > 0) {
			return false;
		} else {
			return true;
		}
	}

	public function validate_user()
	{
		$this->load->library("logger");
		$this->jwtObject = new Jwt_Autorization();
		$received_Token = $this->input->request_headers();


		$ci = &get_instance();


		if (!isset($received_Token['authorization'])) {
			$this->response("you are not authorized", 200);
		}


		$token_info = $this->jwtObject->DecodeToken($received_Token['authorization']);

		if (!$token_info) {
			$this->response(["status"=>false,"message"=>"you are not authenticated"], 200);
		}
	}



	public function validate_forms()
	{

		$method = $this->router->fetch_method();
		$class = $this->router->fetch_class();
		$data = $this->post();
		$is_post_request = $_SERVER['REQUEST_METHOD'] === 'POST';

		$this->load->library('form_validation');
		$this->load->config('form_validation');

		$validationRule = $this->config->item($class . "/" . $method);


		if (!empty($validationRule) && $is_post_request) {


			$this->form_validation->reset_validation()
								->set_rules($validationRule)
								->set_data($data);

			if (!$this->form_validation->run()) {

				return ['status' => false, 'message' => implode('\n', $this->form_validation->error_array())];
			}

			foreach ($data as $key => $value) {
				if (is_array($value) && !empty($validationRule[$key])) {

					$this->form_validation->reset_validation()
											->set_rules($validationRule[$key])
											->set_data($data[$key]);

					if (!$this->form_validation->run()) {

						return ['status' => false, 'message' => implode('\n', $this->form_validation->error_array())];
					}
				}
			}
		}

		return ['status' => true];
	}
}
