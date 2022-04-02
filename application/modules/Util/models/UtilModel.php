<?php


defined('BASEPATH') or exit('Direct access path is not allowed');



class UtilModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }


    public function content($content_title)
    {
        return $this->db->select($content_title)->from("tbl_setting")->get()->row();
    }

    public function contact_us()
    {
        $result = $this->db->select('contact_us')->from('tbl_setting')->get()->row();
        $result = $result ? json_decode($result->contact_us) : null;
        return $result;
    }

    public function lookup($post)
    {

        return $this->db->select('*')
            ->from('tbl_lookup')
            ->where($post)
            ->order_by("value")
            ->get()->result();
    }
    public function language($language)
    {
        return $this->db->select("la.value,lak.key")
            ->from("tbl_language la")
            ->join("tbl_language_key lak","lak.id=la.key_id")
            ->where("language", $language)
            ->get()->result();
    }
}
