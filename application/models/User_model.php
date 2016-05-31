<?php

class user_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function authorize($email, $pwd) {
        $list = "";
        $query = "select * from users where email='$email' and pwd='$pwd'";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $user = new stdClass();
                $user->id = $row->id;
                $user->firstname = $row->firstname;
                $user->lastname = $row->lastname;
                $user->email = $row->email;
            }
            $list.="<br/><div class='calc'>";
            $list.="<form class='calc_form' >";
            $list.= "<br><br>";
            $list.= "<table align='center' border='0' >";
            $list.="<tr><td colspan='2' align='center'>Пожалуйста укажите Email</td></tr>";
            $list.= "<tr>";
            $list.="<td><span class='2'>Ваш Email:</span></td>";
            $list.="<td><span class='2'><input type='text' id='email' name='email'></span></td>";
            $list.="</tr>";
            $list.="<tr>";
            $list.="<td></td><td><span class='2'><button class='calc_order_send' type='button' id='restore_btn'>OK</button></span></td></tr>";
            $list.="</table><br><br>";
            $list.="<div style='text-align:center;' id='forgot_err'></div>";
            $list.="</form>";
            $list.="</div>";
            return $list;
        } // end if $num > 0
        else {
            
        }
        return $list;
    }

}
