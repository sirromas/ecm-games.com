<?php

class login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_login_page() {
        $list = "";
        $list.= "<div class='container-fluid' style='text-align: center;'>";
        $list.="<span class='2'>Логин:</span>";
        $list.="<span class='2'><input type='text' id='username' name='username'></span>";
        $list.="</div>";
        $list.= "<div class='container-fluid' style='text-align: center;'>";
        $list.="<span class='2'>Пароль:</span>";
        $list.="<span class='2'><input type='text' id='pwd' name='pwd'></span>";
        $list.="</div>";

        return $list;
    }

}
