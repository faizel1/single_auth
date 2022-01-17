<?php


defined('BASEPATH') or exit('Direct access path is not allowed');



class UserModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library("Process");
        $this->load->library("File");
    }


    public function update_profile($post)
    {


        $this->db->trans_begin();


        $image = isset($post["image"]) ? $post['image'] : null;
        $interested_area = isset($post['interested_area']) ? $post['interested_area'] : null;
        unset($post['image'], $post['interested_area']);

        $this->db->update("tbl_account", $post, ["id" => $post["id"]]);
        if (!$this->db->affected_rows() > 0) {
            $result = $this->process->post_process(false);
        }

        $this->db->update("tbl_client", ["interested_area" => $interested_area], ["account_id" => $post["id"]]);
        if (!$this->db->affected_rows() > 0) {
            $result = $this->process->post_process(false);
        }

        if (isset($image)) {

            $result = $this->file->save_file($image, "client/$post[id]", 'profile');
        }

        if (!$result["status"] && isset($image)) {

            $this->file->delete("uploads/client/$post[id]/profile.jpeg");
        }

        if ($result["status"]) {
            $this->db->trans_commit();
            $result["message"] = "saved successfully";
        } else {
            $this->db->trans_rollback();
        }


        return $result;
    }
    public function user_detail($user_id)
    {

        // $this->load->database()
        $data = new stdClass();

        $data->profile = $this->db->select("acc.id,acc.phone_number,acc.email,interested_area,acc.full_name")
            ->from("tbl_account acc")
            ->join("tbl_client", "tbl_client.account_id=acc.id", "left")
            ->where("account_id", $user_id)
            ->limit(1)
            ->get()->row();

        $data->address = $this->db->select('id,phone_number,region,city,sub_city,location,is_default')
            ->from("tbl_address")
            ->where("client_id", $user_id)
            ->get()->result();

        $data->payment = $this->db->select('id,bank_name,branch,account_no,is_default')
            ->from("tbl_bank_account")
            ->where("user_id", $user_id)
            ->get()->result();
        if (isset($data->profile)) {

            $data->profile->interested_area = isset($data->profile->interested_area) ? json_decode($data->profile->interested_area) : null;
        }
        return $data;
    }



    public function detail_address($id)
    {
        return  $this->db->select("*")->from("tbl_address")->where("id", $id)->get()->row();
    }

    public function default_address($user_id)
    {

        return  $this->db->select("*")->from("tbl_address")->where("client_id", $user_id)->where("is_default=1")->get()->row();
    }

    public function profile($id)
    {
        return $this->db->select('phone_number,email,full_name')->from('tbl_account')->where('id', $id)->get()->row();
    }
}
