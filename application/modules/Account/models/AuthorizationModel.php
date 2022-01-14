<?php


defined('BASEPATH') or exit('Direct access path is not allowed');



class AuthorizationModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function authorize($post)
    {
        $role = false;
        $result = $this->role($post);

        if (isset($result)) {

            foreach ($result as $value) {
                if ($value->page == $post['page']) {
                    return $value->{$post['action']};
                }
            }
        }
        return $role;
    }


    public function role($post)
    {
        $result =  $this->db->select("role")
            ->from("tbl_account acc")
            ->join("tbl_group", "tbl_group.id=acc.group_id")
            ->where("acc.id", $post['id'])
            ->get()->row();

        if ($result)
            return json_decode(json_decode($result->role));
        else
            return null;
    }
}
