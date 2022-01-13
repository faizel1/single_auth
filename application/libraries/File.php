<?php defined("BASEPATH") or exit("No direct script access allowed");

require __DIR__."/REST.php";
use Restserver\Libraries\REST;

class File
{


	public function __construct()
	{
		$ci = &get_instance();

		$ci->config->load('upload');
	}
	/**
	 * []/string $data is the data to upload array or a single file
	 * 
	 * String $upload_path where to upload
	 * 
	 * String $file_name the name of the file
	 * 
	 * String $file_type file type of the data file or image
	 * 
	 * 
	 */
	public function save_file($data, $upload_path, $file_name, $file_type = false)
	{


		$message = "";

		$ci = &get_instance();
		$ci->config->load('upload');



		$upload_path = config_item('upload_path') . "/" . $upload_path;

		if (is_array($data)) {
			$upload_path .= $file_name;
			$file_name = false;
		} else {
			$data = [$data];
		}

		if (!file_exists($upload_path)) {
			mkdir($upload_path, 0777, true);
		}


		foreach ($data as &$value) {

			$message = $this->upload_file($value, $upload_path, $file_type, $file_name);

			if (!$message['status']) {
				$this->delete($upload_path);

				break;
			}
		}

		return $message;
	}




	private function upload_file($data, $upload_path, $file_type, $file_name)
	{

		$ci = &get_instance();
		$ci->config->load('upload');




		$message = [
			"status" => true,
			"statusCode" => 200,
			"message" => "file saved successfully.",
		];

		$file_info = explode('/', explode(':', substr($data, 0, strpos($data, ';')))[1]);

		$ext = $file_info[1];
		$file_type = $file_type ? $file_type : $file_info[0];

		$allowed_types = $this->allowed_types($ext, $file_type);


		$file_name = $file_name ? $file_name : time();

		$upload_path = $upload_path . "/" . $file_name . "." . $ext;

		$data = base64_decode(explode(',', $data)[1]);



		if ($allowed_types) {

			if (!file_put_contents($upload_path, $data)) {

				$message = [
					"status" => false,
					"statusCode" => REST::HTTP_NON_AUTHORITATIVE_INFORMATION,
					"message" => "Unable to upload the file.Please Check Your Permission",
				];
			}

			if ($message['status'] && $file_type == "image") {
				$ext = "jpg" ? "jpeg" : $ext;
				$compress =	"imagecreatefrom$ext";
				$image = $compress($upload_path);
				imagejpeg($image, $upload_path, config_item('compress_quality'));
			}
		} else {
			$message = [
				"status" => false,
				"statusCode" => REST::HTTP_NON_AUTHORITATIVE_INFORMATION,
				"message" => "file type is not allowed.",
			];
		}

		return $message;
	}





	public function delete($path)
	{

		$ci = &get_instance();
		$ci->config->load('upload');

		$upload_path = config_item('upload_path') . "/" . $path;

		$path = FCPATH . $upload_path;

		$message = [
			"status" => true,
			"statusCode" => REST::HTTP_OK,
			"message" => "delete successfully.",
		];

		if (file_exists($path)) {

			if (is_dir($path)) {

				if (delete_files($path, TRUE)) {

					if (!rmdir($path)) {
						$message = ['status' => false, 'message' => 'unable to delete folder.'];
					}
				} else {
					$message = ['status' => false, 'message' => 'unable to delete file inside the folder.'];
				}
			} else {
				if (!unlink($path)) {
					$message = [
						"status" => false,
						"statusCode" => REST::HTTP_CONFLICT,
						"message" =>
						"unable to delete file. please review folder permissions.",
					];
				}
			}
		}

		return $message;
	}

	private function allowed_types($ext, $file_type = "image")
	{
		$ci = &get_instance();
		$ci->config->load('upload');

		if ($file_type == "image") {

			$allowed_types = config_item('allowed_types_image');
		} else {
			$allowed_types = config_item('allowed_types_file');
		}

		foreach ($allowed_types as $value) {
			if ($value == $ext) {
				return true;
			}
		}
		return false;
	}
}
