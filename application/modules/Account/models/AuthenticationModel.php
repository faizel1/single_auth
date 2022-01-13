<?php

use chriskacerguis\RestServer\RestController;

defined('BASEPATH') or exit('Direct access path is not allowed');



class AuthenticationModel extends MainModel
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('logger');
        $this->load->library("File");
    }



    public function change_password($post)
    {
        $select = "acc.password";
        $valid = $this->db->select($select)->from('tbl_account as acc')->where("acc.id", $post['id'])->get()->row();

        if (!($valid && (password_verify($post['old_password'], $valid->password)))) {
            return ['status' => false, 'message' => 'The old Password is wrong'];
        }
        $pass = password_hash($post['new_password'], PASSWORD_BCRYPT);

        if ($this->db->update('tbl_account', ["password" => $pass])) {
            return ['status' => true, 'message' => "Your password is changed successfully"];
        }
        return ['status' => false, 'message' => 'Something is wrong try again'];
    }

    public function check_pin($post)
    {
        $this->objOfJwt = new Jwt_Autorization();

        $data = $this->db->select('id')
                        ->from('tbl_account')
                        ->where('email', $post['email'])
                        ->where('reset_pin', $post['pin'])
                        ->get()->row();

        if ($data) {
            return ['status' => true, 'message' => $data];
        } else {
            return ['status' => false, 'message' => "Wrong pin code, Please try again"];
        }
    }


    public function find_account($post)
    {
        $message = ['status' => false, 'message' => "your account could not be found"];

        if (isset($post['email'])) {
            $result = $this->db->select('full_name,id,phone_number,email')
                                ->from('tbl_account')
                                ->where('email', $post['email'])
                                ->get()->row();
            if ($result) {
                $message = ['status' => true, 'message' => $result];
            }
        } elseif (isset($post['phone_number'])) {
            $result = $this->db->select('full_name,id')->from('tbl_account')->where('phone_number', $post['phone_number'])->get()->row();
            if ($result) {
                $message = ['status' => true, 'message' => $result];
            }
        }

        if ($message['status']) {
            $message = $this->send_pin($post);
        }

        return $message;
    }



    public function login($post)
    {
     

        $result = $this->db->select("id,password,full_name,phone_number,project_type")->from('tbl_account ')->where("email", $post['email'])->get()->row();

        if (!($result && (password_verify($post['password'], $result->password)) && $result->project_type==$post['project_type']   )) {
            return ['status' => false, 'message' => 'Wrong Email or Password'];
        }

        unset($result->password,$result->project_type);


        return ['status' => true, 'message' => $result];
    }


    public function send_pin($post)
    {
        return ['status' => true, 'message' => 1234];


        $this->load->helper('email');

        $pin = rand(1000, 9999);
        $to = $post['email'];
        $subject = "Message rester Pin code";
        $message = "Your reset pin code is " . $pin;
        if (send($to, $subject, $message)) {
            $this->db->where('email', $post['email']);
            $this->db->update('tbl_account', array('reset_pin' => $pin));
            return ['status' => true, 'message' => $pin];
        }
        return ['status' => false, 'message' => "Something is Wrong Please Try again"];
    }




    public function recover_password($post)
    {
        $pass = password_hash($post['password'], PASSWORD_BCRYPT);
        $array = array('password' => $pass, 'reset_pin' => null);

        $this->db->where('id', $post['id']);

        if ($this->db->update('tbl_account', $array)) {
            return ['status' => true, 'message' => "Your password is changed successfully"];
        } else {
            return ['status' => false, 'message' => "Your password could not be set, please try again later"];
        }
    }
}
