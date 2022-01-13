<?php


defined('BASEPATH') or exit('Direct access path is not allowed');

class ApplicationModel extends MainModel
{
	public function __construct()
	{
		parent::__construct();
	}
	public function save_supplier($post)
	{
		$_POST["id"] = $post["address"]["id"];

		$related_tbl = ['address' => "tbl_address", 'contact_person_address' => "tbl_address"];

		$result = $this->Single_Save("tbl_supplier", $post, false, $related_tbl);

		if ($result["status"]) {

			if ($result["status"]) {
				$this->db->trans_commit();
				$result["message"] = "saved successfully";
			} else {
				$this->db->trans_rollback();
			}
		}

		return $result;
	}
}
