<?php

use chriskacerguis\RestServer\RestController;

class MainModel extends CI_Model
{
	public function __construct()
	{
		$this->load->library("Process");
	}

	// this method is used to save ingular form records
	public function Single_Save(
		$table,
		&$data,
		$take_action = true,
		$related_tbl = [],
		$parent_id = null
	) {
		$this->db->trans_begin();


		if (count($related_tbl) > 0) {
			$result = $this->process->pre_process(
				$related_tbl,
				$data,
				$parent_id
			);
		}

		$this->process->unset_fields($data);

		try {
			// check if the operation is to create new or update the existing record
			if (!isset($data["id"])) {
				$_POST["create"] = true;

				$data["id"] = $parent_id ?? uuid_gen();

					$id =$table=="tbl_order"? $data["id"]:null;
				

				$this->db->insert($table, $data);
			} else {
				$_POST["create"] = false;
				$this->db->update($table, $data, ["id" => $data["id"]]);
			}

			isset($_POST["multi_data"])
				? $this->process->save_multi_data()
				: null;

			return $this->process->post_process($take_action, $id);
		} catch (Exception $exc) {
			$this->logger->exception_error($exc);

			return [
				"status" => false,
				"statusCode" => RestController::HTTP_INTERNAL_SERVER_ERROR,
				"message" => "unable to save the data.",
			];
		}
	}

	// this method is used to save multiple form records
	public function Multi_Save($table, &$data, $rule, $take_action = true)
	{

		$this->process->pre_process_multi_data($data);

		$this->db->trans_begin();

		try {
			// create new or update the existing record
			count($data["new"]) > 0
				? $this->db->insert_batch($table, $data["new"])
				: null;
			count($data["existing"]) > 0
				? $this->db->update_batch($table, $data["existing"], "id")
				: null;

			return $this->process->post_process($take_action);
		} catch (Exception $exc) {
			$this->logger->exception_error($exc);

			return [
				"status" => false,
				"statusCode" => RestController::HTTP_INTERNAL_SERVER_ERROR,
				"message" => "unable to save the data.",
			];
		}
	}

	public function Delete($table, $ids, $take_action = true)
	{
		$this->db->trans_begin();

		try {
			$this->db->where_in("id", $ids)->delete($table);

			$error = $this->db->error();
			$code = $error["code"];

			if ($code === 0) {
				$message = "deleted successfully.";
			} elseif ($code === 1451) {
				$message =
					"you can not delete this record, because other record use this record as a source of data.";
			} else {
				$message = $error["message"];
			}

			if ($this->db->trans_status() && !$code) {
				if ($take_action) {
					$this->db->trans_commit();
				}

				return [
					"status" => true,
					"statusCode" => RestController::HTTP_OK,
					"message" => $message,
				];
			} else {
				$take_action ? $this->db->trans_rollback() : null;
				$this->logger->database_error();

				return [
					"status" => false,
					"statusCode" => RestController::HTTP_NON_AUTHORITATIVE_INFORMATION,
					"message" => $message,
				];
			}
		} catch (Exception $exc) {
			$this->logger->exception_error($exc);
		}
	}

	public function Detail($table, $id, $r_ship = [])
	{
		$result = $this->db->get_where($table, ["id" => $id])->row();

		if (isset($result)) {
			foreach ($r_ship as $rel) {
				if ($rel["tbl"] == "tbl_address") {
					$result->{$rel["alias"]} = $this->db
						->select("add.*,reg.id region_id, city.id city_id")
						->from("tbl_address add")
						->join(
							"tbl_lookup reg",
							"reg.value = add.region",
							"left"
						)
						->join(
							"tbl_lookup city",
							"city.value = add.city",
							"left"
						)
						->where(["add.$rel[key]" => $result->{$rel["value"]}])
						->get()
						->result();
				} else {
					$result->{$rel["alias"]} = $this->db
						->get_where($rel["tbl"], [
							$rel["key"] => $result->{$rel["value"]},
						])
						->result();
				}
			}
		}

		unset(
			$result->password,
			$result->reset_pin,
			$result->is_default_password
		);

		return [
			"statusCode" => $result
				? RestController::HTTP_OK
				: RestController::HTTP_NON_AUTHORITATIVE_INFORMATION,
			"data" => $result,
		];
	}
}
