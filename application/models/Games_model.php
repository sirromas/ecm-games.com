<?php

class Games_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();        
        $this->load->library('session');
        $this->load->helper('url');
    }    

    public function get_games_left_list() {
        $list = "";
        $query = "SELECT * FROM `games` order by gamName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<li><a href='" . $this->config->item('base_url') . "index.php/games/game_block/$row->gamID' title='$row->gamName'>$row->gamName</a></li>";
        }
        return $list;
    }

    public function get_games_center_block() {
        $list = "";
        $list.="<h1>Магазин игровой валюты</h1>";
        $list.="<div class='category-img'>";
        $list.="<nav>";
        $query = "SELECT * FROM `games` order by gamName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<a href='" . $this->config->item('base_url') . "index.php/games/game_block/$row->gamID' title='$row->gamName'><img src='" . $this->config->item('base_url') . "assets/icon/$row->icon' title='$row->gamName' alt='$row->gamName'/></a>";
        }
        $list.="</nav>";
        $list.="</div>";
        $list.="<div></div>";
        return $list;
    }

    public function edit($id) {
        $list = "";
        
    }

}
