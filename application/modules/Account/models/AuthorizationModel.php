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
                if (strcasecmp($value->page,$post['page'])==0) {
                     if($value->{$post['action']}){
                        $role=$value->{$post['action']};
                        break;
                    };
                }
            }
        }

        if (!$role)
            $message =    ["status" => false, "message" => "Your are not Authorized"];
        else
            $message = ["status" => true, "message" => "success"];

        return $message;
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

    public function update_firebase_token($post)
    {
        $result = $this->db->update("tbl_account", ["firebase_token"=> $post['firebase_token']],["id"=>$post['id']]);
        return $result;

    } 

    
}
