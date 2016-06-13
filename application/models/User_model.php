<?php

class user_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function validate_user() {
        $firstname = $this->session->userdata('firstname');
        $lastname = $this->session->userdata('lastname');
        $email = $this->session->userdata('email');
        if ($firstname != '' && $lastname != '' && $email != '') {
            return true;
        } // end if $firstname!='' && $lastname!='' && $email!=''
        else {
            return false;
        } // end else
    }

    public function authorize($email, $pwd) {
        $query = "select * from users where email='$email' and pwd='$pwd'";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $userdata = array('id' => $row->id,
                    'firstname' => $row->firstname,
                    'lastname' => $row->lastname,
                    'email' => $row->email,
                    'type' => $row->type);
                $this->session->set_userdata($userdata);
                return $userdata['type'];
            } // end foreach
        } // end if $num > 0
        else {
            return false;
        }
    }

    public function get_user_dashboard($type) {
        $list = "";
        $status = $this->validate_user();
        if ($status) {
            if ($type == 3) {
                $games = $this->get_games_list();
                $servers = $this->get_servers_list();
                $deals = $this->get_deals_list();
                $users = $this->get_users_list();
                $other = $this->get_others_list();
                $list.="<br/><div class='calc'>";
                $list.="<form class='calc_form' >";
                $list.= "<br><br>";
                $list.= "<table align='center' border='0' style='width: 100%;'>";
                $list.="<tr>";
                $list.= "<td><span id='games_container'>$games</span><td>";
                $list.= "<td><span id='servers_container'>$servers</span><td>";
                $list.= "<td><span id='deals_container'>$deals</span><td>";
                $list.= "<td><span id='user_container'>$users</span><td>";
                $list.= "<td><span id='report_containers'>$other</span><td>";
                $list.= "</tr>";
                $list.="</table><br><br>";
                $list.="<div style='text-align:center;' id='forgot_err'></div>";
                $list.="</form>";
                $list.="</div>";
            } // end if $type==3        
            else if ($type == 1) {
                
            } // end if $type==1
            else if ($type == 2) {
                
            } // end if $type==2
            return $list;
        } // end if $status
        else {
            redirect(base_url());
        } // end else
    }

    public function get_games_list() {
        $list = "";
        $list.="<select id='games' style='width:95px;'>";
        $list.="<option value='0' selected>Игры</option>";
        $query = "select * from games order by gamName";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $list.="<option value='$row->gamID' >$row->gamName</option>";
            } // end foreach
        } // end if $num > 0        
        $list.="</select>";
        return $list;
    }

    public function get_deals_list() {
        $list = "";
        $list.="<select id='deals' style='width:95px;'>";
        $list.="<option value='0' selected>Сделки</option>";
        $list.="</select>";
        return $list;
    }

    public function get_users_list() {
        $list = "";
        $list.="<select id='users' style='width:95px;'>";
        $list.="<option value='0' selected>Пользователи</option>";
        $query = "select * from users order by lastname ";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $list.="<option value='$row->id' >$row->firstname $row->lastname</option>";
            } // end foreach
        } // end if $num > 0                
        $list.="</select>";
        return $list;
    }

    public function get_servers_list() {
        $list = "";
        $list.="<select id='servers' style='width:95px;'>";
        $list.="<option value='0' selected>Сервера</option>";
        $query = "select * from gameservers order by gasName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='$row->gasID'>$row->gasName</option>";
        }
        $list.="</select>";
        return $list;
    }

    public function get_others_list() {
        $list = "";
        $list.="<select id='other' style='width:95px;'>";
        $list.="<option value='0' selected>Другое</option>";
        $list.="<option value='add_game'><a href='" . $this->config->item('base_url') . "index.php/games/add_game' style='color: #000000;font-size: 14px;text-decoration: none;'>Добавить игру</a></option>";
        $list.="<option value='add_server'><a href='" . $this->config->item('base_url') . "index.php/games/add_server' style='color: #000000;font-size: 14px;text-decoration: none;'>Добавить сервер</a></option>";
        $list.="<option value='add_user'><a href='" . $this->config->item('base_url') . "index.php/user/add_user' style='color: #000000;font-size: 14px;text-decoration: none;'>Добавить пользователя</a></option>";
        $list.="<option value='exit'><a href='" . $this->config->item('base_url') . "index.php/user/logout' style='color: #000000;font-size: 14px;text-decoration: none;'>Выход</a></option>";
        $list.="</select>";
        return $list;
    }

    public function get_exit_page() {
        $list = "";
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' >";
        $list.= "<br><br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<input type='hidden' id='type' value='" . $this->session->userdata('type') . "'>";
        $list.="<tr>";
        $list.= "<td colspan='2'>Выити из системы?&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-xs' id='logout'>Да</button> &nbsp;<button type='button' class='btn btn-primary btn-xs' id='cancel_logout' >Нет</button><td>";
        $list.= "</tr>";
        $list.="</table><br><br>";
        $list.="<div style='text-align:center;' id='forgot_err'></div>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url());
    }

}
