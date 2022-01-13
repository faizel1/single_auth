<?php
function send($message, $email, $subject)
{
	$ci = &get_instance();
	$result = false;

	$config = $ci->db->get("tbl_setting")->row()->value;
	$config = json_decode($config, true);

	if ($config) {
		$connected = @fsockopen($config["host"], $config["port"]);

		if ($connected) {
			$ci->load->library("email");

			$ci->email->from($config["email"], "Smart Delivery");
			$ci->email->to($email);
			$ci->email->subject($subject);
			$ci->email->message($message);

			$result = $ci->email->send();
		}
	}

	return $result;
}
