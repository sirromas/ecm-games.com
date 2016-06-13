<!DOCTYPE html><html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<?php

/**
 * Description of games
 *
 * @author sirromas
 */
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'] . '/games/ajax/class.pdo.database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/games/assets/editor/fckeditor.php';

class games {

    public $db;
    public $editor_path;
    public $site_path;

    public function __construct() {
        $this->db = new pdo_db();
        $this->editor_path = 'http://' . $_SERVER['SERVER_NAME'] . '/games/assets/editor/';
        $this->site_path='http://' . $_SERVER['SERVER_NAME'] . '/games/';
    }

    public function get_games_list() {
        $list = "";
        $list.="<select id='games' style='width:95px;'>";
        $list.="<option value='0' selected>Игры</option>";
        $query = "select * from games order by gamName";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<option value='" . $row['gamID'] . "'>" . $row['gamName'] . "</option>";
            }
        } // end if $num > 0        
        $list.="</select>";
        return $list;
    }

    public function get_game_servers($id) {
        $servers = array();
        $query = "select * from gameservers where gasGameID=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $server = new stdClass();
            $server->id = $row['gasID'];
            $server->name = $row['gasName'];
            $server->exchangerate = $row['gasKurs'];
            $servers[] = $server;
        }
        return $servers;
    }

    public function get_game_content($id) {
        $query = "select * from gamescontent where gmcGameID=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contentid = $row['gmcContentID'];
        }

        $query2 = "select * from content where cntID=$contentid and cntLanguageID=2";
        $result2 = $this->db->query($query2);
        while ($row = $result2->fetch(PDO::FETCH_ASSOC)) {
            $content = new stdClass();
            $content->id = $id;
            $content->title = $row['cntTitle'];
            $content->body = $row['cntBody'];
            $content->videourl = $row['cntURL'];
        }
        return $content;
    }

    public function edit($id) {
        $list = "";
        $game_detailes = $this->get_game_details($id); // object        

        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' id='update_game' style='padding-left:15px;' method='post' action=''>";
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
    
    public function get_game_details($id) {
        $list = "";
        $games_list = $this->get_games_list();
        $content = $this->get_game_content($id);
        $servers = $this->get_game_servers($id);
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $game = new stdClass();
            $game->name = $row['gamName'];
            $game->currency = $row['gamMoney'];
            $game->minamount = $row['gamMinCount'];
        }
        $list.="<table>";

        $list.="<tr>";
        $list.="<td>Выбор игры</td><td align='left'>$games_list</td><td></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td>Название игры</td><td align='left'><input type='text' id='title' name='title' value='$game->name'></td><td></td>";
        $list.="<tr>";        
        
        $oFCKeditor = new FCKeditor('editor');
        $oFCKeditor->BasePath = $this->editor_path;
        $oFCKeditor->Value = $content->body;
        //$editor = $oFCKeditor->Create(true);
        
        $list.="<tr>";
        $list.="<td>Описание игры</td><td><div>" . $oFCKeditor->Create(FALSE) . "</div></td>";
        $list.="</tr>";        

        $list.="<tr>";
        $list.="<td >Ссылка на видео</td><td align='left'><input type='text' id='video' name='video' value='$content->videourl'></td><td></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td >Валюта игры</td><td align='left'><input type='text' id='currency' name='currency' value='$game->currency'></td><td></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td >Мин заказ ($)</td><td align='left'><input type='text' id='minamount' name='minamount' value='$game->minamount'></td><td></td>";
        $list.="<tr>";

        if (count($servers) > 0) {
            foreach ($servers as $server) {
                $list.="<tr>";
                $list.="<td>Сервер</td><td><input type='text' id='name_$server->id' name='name_$server->id' value='$server->name'> &nbsp; <input type='text' id='$server->id' name='rate_$server->id' value='$server->exchangerate' style='width:75px;'></td>";
                $list.="</tr>";
            } // end foreach
        } // end if count($servers)>0

        $list.="<tr>";
        $list.="<td><a href='#' onClik='return false;' id='update_game' style='color: #000000;font-size: 14px;text-decoration: none;'>Ok</a></td><td align='left'><a href='$this->site_path'index.php/user/page/3' style='color: #000000;font-size: 14px;text-decoration: none;'>Cancel</a></td>";
        $list.="<tr>";

        $list.="</table>";
        return $list;
    }
    
    public function get_game_content2 ($id) {
        $query = "select * from gamescontent where gmcGameID=$id";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contentid = $row['gmcContentID'];
        }

        $query2 = "select * from content where cntID=$contentid and cntLanguageID=2";
        //echo "Query: " . $query2 . "<br>";
        $result2 = $this->db->query($query2);
        while ($row = $result2->fetch(PDO::FETCH_ASSOC)) {
            $content = new stdClass();
            $content->id = $id;
            $content->title = $row['cntTitle'];
            $content->body = $row['cntBody'];
            $content->videourl = $row['cntURL'];
        }
        return $content;
    }


    public function get_game_content_box ($id) {
        $list = "";        
        $content=$this->get_game_content2($id);
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Описание игры</h4>
                </div>                
                <div class='modal-body'>                                
                
                <div class='container-fluid' style='text-align:left;'>
                <textarea name='body'>$content->body</textarea>
                <script>
                CKEDITOR.replace( 'body' );
                </script>
                </div>            
                
                </div>
                
                <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Отмена</button></span>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='recreate'>Ок</button></span>
                </div>
        </div>
        </div>
        </div>";
        return $list;
    }
    

}

$gm = new games();
$id = $_POST['id'];
$list = $gm->get_game_detailes($id);
echo $list;
