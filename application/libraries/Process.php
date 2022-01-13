<?php defined("BASEPATH") or exit("No direct script access allowed");
require FCPATH . 'vendor/autoload.php';

use chriskacerguis\RestServer\RestController;

class Process
{
	public function pre_process(&$related_tbl,  &$data, $parent_id)
	{
		$ci = &get_instance();
		$result = ["status" => true];

		foreach ($related_tbl as $key => &$tbl) {
			$value = $data[$key];

			if (is_array(reset($value)) && isset($parent_id)) {
				$result = $this->pre_process_multi_data(
					$value,
					$parent_id,
					$tbl
				);


				$_POST["multi_data"]["data"]["deleted"] = $data["deleted"];

				unset($data["deleted"]);
			} else {


				$this->save_single_data($data, $key, $value, $tbl);
			}

			unset($data[$key]);
		}

		return $result;
	}

	public function pre_process_multi_data(&$data, $parent_id, $tbl)
	{
		$ci = &get_instance();
		$new = $existing = [];


		foreach ($data as $key => &$value) {
			if (!isset($value["id"])) {
				$value["id"] = uuid_gen();
				isset($parent_id)
					? ($value["parent_id"] = $parent_id)
					: null;

				array_push($new, $value);
			} else {
				array_push($existing, $value);
			}

			unset($data[$key]);
		}


		$data = ["new" => $new, "existing" => $existing];

		$_POST["multi_data"] = ["data" => $data, "tbl" => $tbl];

		return $data;
	}

	public function save_multi_data()
	{
		$ci = &get_instance();

		$data = $_POST["multi_data"]["data"];
		$table = $_POST["multi_data"]["tbl"];

		if (count($data["new"]) > 0) {
			foreach ($data["new"] as $value)
				$ci->db->insert($table, $value);
		}


		if (count($data["existing"]) > 0) {
			foreach ($data["existing"] as $value)

				$ci->db->update($table, $value, 	["id" => $value["id"]]);
		}


		count($data["deleted"]) > 0
			? $ci->db
			->where_in("id", $data["deleted"])
			->delete($_POST["multi_data"]["tbl"])
			: null;



		// count($data["new"]) > 0

		// 	? $ci->db->insert_batch($_POST["multi_data"]["tbl"], $data["new"])
		// 	: null;

		// count($data["existing"]) > 0
		// 	? $ci->db->update_batch(
		// 		$_POST["multi_data"]["tbl"],
		// 		$data["existing"],
		// 		"id"
		// 	)
		// 	: null;

		// count($data["deleted"]) > 0
		// 	? $ci->db
		// 	->where_in("id", $data["deleted"])
		// 	->delete($_POST["multi_data"]["tbl"])
		// 	: null;

		unset($data["deleted"], $data);
	}

	public function save_single_data(&$data, $key, $value, $tbl)
	{
		$ci = &get_instance();

		if (is_null($value["id"])) {
			$value["id"] = uuid_gen();
			$ci->db->insert($tbl, $value);
		} else {
			$ci->db->update($tbl, $value, ["id" => $value["id"]]);
		}

		in_array($key, [
			"address",
			"shipping_address",
			"contact_person_address",
			"account",
		])
			? ($data[$key . "_id"] = $value["id"])
			: null;
	}

	//preprocess_data
	public function unset_fields(&$data)
	{
		$keys = [
			"image",
			"vehicle_image",
			"owner_image",
			"attachment",
			"detail",
			"deleted_image",
			"deleted_file",
			"files",
		];

		foreach ($keys as &$value) {
			if (array_key_exists($value, $data)) {
				unset($data[$value]);
			}
		}
	}

	//preprocess_data
	public function post_process($take_action, $order_id = null)
	{
		$ci = &get_instance();

		if ($ci->db->trans_status()) {
			// if the operation is successfull, then check to save data or not
			// based on the additional file save operation
			$take_action ? $ci->db->trans_commit() : null;


			$message = [
				"status" => true,
				"statusCode" => RestController::HTTP_OK,
				"message" => "saved successfully.",
			];
			if (isset($order_id)) {
				$message["order_id"] = $order_id;
			}
			return $message;
		} else {
			$take_action ? $ci->db->trans_rollback() : null;

			$ci->logger->database_error();

			return [
				"status" => false,
				"statusCode" => RestController::HTTP_INTERNAL_SERVER_ERROR,
				"message" => "unable to save the data.",
			];
		}
	}

	// filrer query builder
	public function pre_process_query($post)
	{
		$ci = &get_instance();

		$this->sort($post["sort"]);
		$this->filter($post["filter"]);
		$this->search($post);

		$ci->db->limit($post["limit"]);
	}

	public function sort($data)
	{
		$ci = &get_instance();

		foreach ($data as $col) {
			$ci->db->order_by($col["field"], $col["direction"]);
		}
	}

	public function filter($data)
	{
		$ci = &get_instance();

		foreach ($data as $value) {
			if (
				in_array($value["operator"], [
					"startswith",
					"endswith",
					"contains",
				])
			) {
				$operator = $this->operators(
					$value["operator"],
					$value["value"]
				);

				$ci->db->having("$value[field] $operator");
			} else {
				$operator = $this->operators($value["operator"]);
				$ci->db->having("$value[field] $operator '$value[value]'");
			}
		}
	}

	public function search($data)
	{
		$ci = &get_instance();

		if (isset($data["searchString"])) {
			foreach ($data["where_col"] as $col) {
				$ci->db->or_having("$col LIKE '%$data[searchString]%'");
			}
		}
	}

	public function operators($key, $value = null)
	{
		$operator = [
			"startswith" => "LIKE '$value%'",
			"endswith" => "LIKE '%$value'",
			"contains" => "LIKE '%$value%'",
			"equal" => "=",
			"notequal" => "!=",
			"lessthan" => "<",
			"lessthanorequal" => "<=",
			"greaterthan" => ">",
			"greaterthanorequal" => ">=",
		];

		return $operator[$key];
	}
}
