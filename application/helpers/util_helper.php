<?php
use Restserver\Libraries\REST;

function uuid_gen()
{
	return str_replace(".", "", uniqid("", true));
}

//this method is used to generate & return random Alph-numeric password
function password_generate()
{
	$data =
		'1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz!@*&/?$';
	return substr(str_shuffle($data), 0, 8);
}

//this method is used to send randomly generated password for password reset purpose.
function send_password($post)
{
	$ci = &get_instance();
	$ci->load->library("logger");

	try {
		if (
			send(
				$post["message"],
				$post["email"],
				"New password from Smart Delivery"
			)
		) {
			return [
				"status" => true,
				"statusCode" => REST::HTTP_OK,
				"message" =>
					"New password sent to your email address successfully.",
			];
		} else {
			return [
				"status" => false,
				"statusCode" => REST::HTTP_INTERNAL_SERVER_ERROR,
				"message" =>
					"unable to send password. please check your network.",
			];
		}
	} catch (Exception $exc) {
		$ci->logger->exception_error($exc);

		return [
			"status" => false,
			"statusCode" => REST::HTTP_INTERNAL_SERVER_ERROR,
			"message" => "unable to send. try again",
		];
	}
}
