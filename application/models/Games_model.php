<?php

class Games_model extends CI_Model {

    public $editor_path;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('user_model');
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

    public function get_game_details($id) {
        $list = "";
        $games_list = $this->user_model->get_games_list();
        $content = $this->get_game_content($id);
        $servers = $this->get_game_servers($id);
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $game = new stdClass();
            $game->name = $row->gamName;
            $game->currency = $row->gamMoney;
            $game->minamount = $row->gamMinCount;
        }
        $list.="<table border='0' align='center' style=''>";

        $list.="<tr>";
        $list.="<td align='left'>Выбор игры</td><td align='left'>$games_list</td><td><a href='#' onClick='return false;' style='color: #000000;font-size: 14px;text-decoration: none;'>Удалить</a></td><td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td align='left'>Название игры</td><td align='left'><input type='text' id='title' name='title' value='$game->name'></td><td><a href='#' onClick='return false;' style='color: #000000;font-size: 14px;text-decoration: none;' id='game_detailes_id_$id'>Описание</a></td>";
        $list.="<input type='hidden' id='id' name='id' value='$id'>";
        $list.="<tr>";

        /*
         * 
          $list.="<tr><td colspan='3'>";
          $list.="<textarea name='body'>$content->body</textarea>
          <script>
          CKEDITOR.replace( 'body' );
          </script>";
          $list.="</td></tr>";
         * 
         */


        $list.="<tr>";
        $list.="<td align='left'>Видео URL</td><td align='left'><input type='text' id='video' name='video' value='$content->videourl'></td><td></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td align='left'>Валюта игры</td><td align='left'><input type='text' id='currency' name='currency' value='$game->currency'></td><td></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td align='left'>Мин заказ ($)</td><td align='left'><input type='text' id='minamount' name='minamount' value='$game->minamount'></td><td></td>";
        $list.="<tr>";

        foreach ($servers as $server) {
            $list.="<tr>";
            $list.="<td align='left'>Сервер</td><td><input type='text' id='name_$server->id' value='$server->name'></td><td><input type='text' id='exchange_$server->id' value='$server->exchangerate'></td><td><a href='#' onClick='return false;' id='update_server_$server->id' style='color: #000000;font-size: 14px;text-decoration: none;'>Обновить</a></td>";
            $list.="</tr>";
        }

        $list.="<tr>";
        $list.="<td></td><td align='left' id='save_game_form'><button>Ok</button></td>";
        $list.="<tr>";

        $list.="</table>";
        return $list;
    }

    public function update_game_content($game) {
        $list = "";
        $query = "update games "
                . "set gamName=" . $this->db->escape($game->title) . ", 	"
                . "gamMoney=" . $this->db->escape($game->currency) . ", "
                . "gamMinCount=" . $this->db->escape($game->minamount) . " "
                . "where gamID=$game->id";
        $this->db->query($query);

        $list.="<br/><div class=''>";
        $list.="<form class='calc_form'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td><span>Параметры игры успешно обнолены. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
        $list.= "</tr>";
        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function get_game_servers($id) {
        $servers = array();
        $query = "select * from gameservers where gasGameID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $server = new stdClass();
            $server->id = $row->gasID;
            $server->name = $row->gasName;
            $server->exchangerate = $row->gasKurs;
            $servers[] = $server;
        }
        return $servers;
    }

    public function get_game_content($id) {
        $query = "select * from gamescontent where gmcGameID=$id";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $contentid = $row->gmcContentID;
        }

        $query2 = "select * from content where cntID=$contentid and cntLanguageID=2";
        //echo "Query: " . $query2 . "<br>";
        $result2 = $this->db->query($query2);
        foreach ($result2->result() as $row) {
            $content = new stdClass();
            $content->id = $id;
            $content->title = $row->cntTitle;
            $content->body = $row->cntBody;
            $content->videourl = $row->cntURL;
        }
        return $content;
    }

    public function edit($id) {
        $list = "";
        $game_detailes = $this->get_game_details($id); // object        

        $list.="<br/><div class=''>";
        $list.="<form class='calc_form' id='update_game' style='padding:15px;' method='post' action='" . $this->config->item('base_url') . "index.php/games/save_game'>";
        $list.= "<br><br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";

        $list.="<tr>";
        $list.= "<td><span id='games_container'>$game_detailes</span><td>";
        $list.= "</tr>";

        $list.="</table><br><br>";
        $list.="<div style='text-align:center;' id='game_err'></div>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function get_game_content_box($id) {
        $list = "";

        $content = $this->get_game_content($id);
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Описание игры</h4>
                </div>                
                <div class='modal-body'>                                
                
                <div class='container-fluid' style='text-align:left;'>
                <input type='hidden' id='id' value='$id'>
                <textarea name='body'>$content->body</textarea>
                <script>
                CKEDITOR.replace( 'body' );
                </script>
                </div>            
                
                </div>
                
                <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Отмена</button></span>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='upd_game'>Ок</button></span>
                </div>
        </div>
        </div>
        </div>";


        return $list;
    }

    public function update_game_content2($game) {
        $query = "select * from gamescontent where gmcGameID=$game->id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $contentid = $row->gmcContentID;
        }

        //echo "Content id: ".$contentid."<br>";

        $query = "update content "
                . "set cntBody=" . $this->db->escape($game->body) . " "
                . "where cntID=$contentid and cntLanguageID=2";
        //echo "Query: ".$query."<br>";
        $this->db->query($query);
    }

}
