<?php

class login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_login_page() {
        $list = "";
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' >";
        $list.= "<br><br><br><br><br><br>";
        $list.= "<table align='center'><tr><td>";
        $list.="<span class='2'>Логин:</span></td>";
        $list.="<td><span class='2'><input type='text' id='username' name='username'></span></td>";
        $list.="</tr>";
        $list.= "<tr>";
        $list.="<td><span class='2'>Пароль:</span></td>";
        $list.="<td><span class='2'><input type='password' id='pwd' name='pwd'></span></td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td></td><td><span class='2'><button class='calc_order_send' type='submit'>OK</button></span></td>";
        $list.="</tr></table><br><br><br><br><br><br><br><br><br><br><br><br><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

}
