<?php


defined('BASEPATH') or exit('Direct access path is not allowed');



class AddressModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library("Process");
        $this->load->library("File");
    }


    public function list($id)
    {
        return $this->db->select('*')
            ->from('tbl_address')
            ->where('client_id', $id)
            ->get()->result(); 
    } 

    public function detail_address($id)
    {
        return  $this->db->select("*")
            ->from("tbl_address")
            ->where("id", $id)
            ->get()->row();
    }

    public function default_address($user_id)
    {

        return  $this->db->select("*")
            ->from("tbl_address")
            ->where("client_id", $user_id)
            ->where("is_default=1")
            ->get()->row();
    }

    public function profile($id)
    {
        return $this->db->select('phone_number,email,full_name')
            ->from('tbl_account')
            ->where('id', $id)
            ->get()->row();
    }

}
