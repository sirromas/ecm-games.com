<?php

class Server_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('user_model');
    }

    public function update($server) {
        $query = "update gameservers "
                . "set gasName=" . $this->db->escape($server->name) . " , "
                . "gasKurs=" . $this->db->escape($server->rate) . " "
                . "where gasID=$server->id";
        $this->db->query($query);
    }

    public function get_games_list() {
        $list = "";
        $list.="<select id='game' name='game'  style='width:162px;'>";
        $list.="<option value='0' selected>Игра</option>";
        $query = "select * from games order by gamName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='$row->gamID'>$row->gamName</option>";
        }
        $list.="</select>";
        return $list;
    }

    public function get_add_server_page() {
        $list = "";
        $status = $this->user_model->validate_user();
        if ($status) {
            $type = $this->session->userdata('type');
            if ($type == 3) {
                $games = $this->get_games_list();
                $list.="<table align='center' border='0'>";

                $list.="<tr>";
                $list.="<td>Имя сервера</td><td align='left'><input type='text' id='name' name='name'></td>";
                $list.="<td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Курс сервера</td><td align='left'><input type='text' id='rate' name='rate'></td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Игра</td><td align='left'>$games</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td colspan='2'><span id='server_err'></span></td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td></td><td><button>Ok</button></td>";
                $list.="</tr>";

                $list.="</table>";
            } // end if $type==3
            else {
                redirect(base_url());
            }
        } // end if $status
        else {
            redirect(base_url());
        }
        return $list;
    }

    public function add_server() {
        $list = "";
        $server = $this->get_add_server_page();
        $list.="<br/><div class=''>";
        $list.="<form class='calc_form' id='add_server' method='post' action='" . $this->config->item('base_url') . "index.php/servers/add_server_done/'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td>$server<td>";
        $list.= "</tr>";
        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function get_game_name($id) {
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $name = $row->gamName;
        }
        return $name;
    }

    public function add_server_done($server) {
        $list = "";
        $query = "insert into gameservers "
                . "(gasGameID,"
                . "gasName,"
                . "gasKurs) "
                . "values("
                . "'$server->game',"
                . "'$server->name',"
                . "'$server->rate')";
        $this->db->query($query);
        $list.="<br/><div class=''>";
        $list.="<form class='calc_form'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td align='center'>&nbsp;&nbsp;<span>Сервер успешно добавлен. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
        $list.= "</tr>";
        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

}
