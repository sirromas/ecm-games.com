<?php

class Games_model extends CI_Model {

    public $icon_path;
    public $exchange_xml_path = "http://resources.finance.ua/ru/public/currency-cash.xml";

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('user_model');
        $this->icon_path = $_SERVER['DOCUMENT_ROOT'] . "/games/assets/icon";
    }

    public function get_games_left_list() {
        $list = "";
        $status = $this->user_model->validate_user();
        $type = $this->session->userdata('type');
        $query = "SELECT * FROM `games` order by gamName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            if ($status) {
                if ($type == 3) {
                    if ($row->action == 1) {
                        $list.="<li><a href='" . $this->config->item('base_url') . "index.php/games/edit/$row->gamID' title='$row->gamName'>$row->gamName</a>&nbsp;<a href='#' onClick='return false;' style='color:red;font-weight:bold;' data-toggle='popover' title='Акция' data-content='$row->action_text'>Акция</a></li>";
                    } // end if $row->action == 1
                    else {
                        $list.="<li><a href='" . $this->config->item('base_url') . "index.php/games/edit/$row->gamID' title='$row->gamName'>$row->gamName</a></li>";
                    }
                } // end if $type==3
                else {
                    if ($row->action == 1) {
                        $list.="<li><a href='" . $this->config->item('base_url') . "index.php/user/page/$type/$row->gamID' title='$row->gamName'>$row->gamName</a>&nbsp;<a href='#' onClick='return false;' style='color:red;font-weight:bold;' data-toggle='popover' title='Акция' data-content='$row->action_text'>Акция</a></li>";
                    } // end if $row->action == 1
                    else {
                        $list.="<li><a href='" . $this->config->item('base_url') . "index.php/games/edit/$row->gamID' title='$row->gamName'>$row->gamName</a></li>";
                    }
                } // end else                
            } // end if $status 
            else {
                if ($row->action == 1) {
                    $list.="<li><a href='" . $this->config->item('base_url') . "index.php/user/page/0/$row->gamID' title='$row->gamName'>$row->gamName</a>&nbsp;<a href='#' onClick='return false;' style='color:red;font-weight:bold;' data-toggle='popover' title='Акция' data-content='$row->action_text'>Акция</a></li>";
                } // end if $row->action==1
                else {
                    $list.="<li><a href='" . $this->config->item('base_url') . "index.php/games/edit/$row->gamID' title='$row->gamName'>$row->gamName</a></li>";
                } // else
            } // end else
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
            $list.="<a href='" . $this->config->item('base_url') . "index.php/user/page/0/$row->gamID' title='$row->gamName'><img src='" . $this->config->item('base_url') . "assets/icon/$row->icon' title='$row->gamName' alt='$row->gamName'/></a>";
        }
        $list.="</nav>";
        $list.="</div>";
        $list.="<div></div>";
        return $list;
    }

    public function get_game_action_block($id) {
        $list = "";
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $action = $row->action;
            $action_text = $row->action_text;
        }
        $list.="<table align='center'>";
        if ($action == 1) {
            $list.="<tr>";
            $list.="<td align='center' style='padding:5px;'><input type='checkbox' id='game_action' name='game_action' value='action_enable' checked>&nbsp;Включить</td>";
            $list.="</tr>";
        } // end if $action==1
        else {
            $list.="<tr>";
            $list.="<td align='center' style='padding:5px;'><input type='checkbox' id='game_action' name='game_action' value='action_enable'>&nbsp;Включить</td>";
            $list.="</tr>";
        } // end else

        $list.="<tr>";
        $list.="<td align='center' style='padding:5px;'><textarea id='game_action_text' name='game_action_text'>$action_text</textarea></td>";
        $list.="</tr>";


        $list.="</table>";
        return $list;
    }

    public function get_game_details($id) {
        $list = "";
        $games_list = $this->user_model->get_games_list();
        $action_block = $this->get_game_action_block($id);
        $servers = $this->get_game_servers($id);
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $game = new stdClass();
            $game->name = $row->gamName;
            $game->currency = $row->gamMoney;
            $game->minamount = $row->gamMinCount;
            $game->min = $row->min_price;
            $game->max = $row->max_price;
        }
        $list.="<table border='0' align='center' style=''>";

        $list.="<tr>";
        $list.="<td align='left'>Выбор игры</td><td align='left'>$games_list</td><td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td align='left'>Название игры*</td><td align='left'><input type='text' id='title' name='title' value='$game->name'></td><td><a href='#' onClick='return false;' style='color: #000000;font-size: 14px;text-decoration: none;' id='game_detailes_id_$id'>Описание</a></span></td><td>&nbsp;&nbsp;&nbsp;<a href='#' onClick='return false;' style='color: #000000;font-size: 14px;text-decoration: none;' id='del_game_$id'>Удалить</a></td>";
        $list.="<input type='hidden' id='id' name='id' value='$id'>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td align='left'>Валюта игры*</td><td align='left'><input type='text' id='currency' name='currency' value='$game->currency'></td><td></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td align='left'>Мин заказ ($)*</td><td align='left'><input type='text' id='minamount' name='minamount' value='$game->minamount'></td><td></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td align='left'>Мин цена ($)*</td><td align='left'><input type='text' id='min_price' name='min_price' value='$game->min'></td><td></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td align='left'>Макс цена ($)*</td><td align='left'><input type='text' id='max_price' name='max_price' value='$game->max'></td><td></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.="<td align='left'>Акция</td><td align='left'>$action_block</td>";
        $list.="<tr>";

        foreach ($servers as $server) {
            $list.="<tr>";
            $list.="<td align='left'>Сервер</td><td><input type='text' id='name_$server->id' value='$server->name'></td><td><input type='text' id='exchange_$server->id' value='$server->exchangerate' style='width:75px;'></td><td><a href='#' onClick='return false;' id='update_server_$server->id' style='color: #000000;font-size: 14px;text-decoration: none;'>Обновить</a></td>";
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
                . "gamMinCount=" . $this->db->escape($game->minamount) . " , "
                . "action=$game->game_action, "
                . "action_text=" . $this->db->escape($game->game_action_text) . ", "
                . "min_price='$game->min_price', "
                . "max_price='$game->max_price' "
                . "where gamID=$game->id";
        //echo "Query: ".$query."<br>";
        $this->db->query($query);

        $list.="<br/><div class=''>";
        $list.="<form class='calc_form'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td align='center'>&nbsp;&nbsp;<span>Параметры игры успешно обнолены. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
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

        $list.="<br/><div class='' id='game_container'>";
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
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel_edit_game'>Отмена</button></span>
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

    public function get_servers_list() {
        $list = "";
        $list.="<select id='server' name='server[]' multiple='multiple' style='width:285px;'>";
        $list.="<option value='0' selected>Сервер</option>";
        $query = "select * from gameservers order by gasName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='$row->gasID'>$row->gasName</option>";
        }
        $list.="</select>";
        return $list;
    }

    public function get_add_game_page() {
        $list = "";

        $list.="<table align='center' border='0'>";

        $list.="<tr>";
        $list.="<td>Картинка игры*</td><td><input type='file' id='files' name='files' style='width:185px;'></td><td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Название игры*</td><td><input type='text' id='name' name='name'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Денежная единица*</td><td><input type='text' id='currency' name='currency'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Мин. сумма сделки ($)* </td><td><input type='text' id='min_amount' name='min_amount'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td align='center' colspan='2'><span id='game_err'></span></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>&nbsp;</td><td><button>Добавить</button></td>";
        $list.="</tr>";

        $list.="</table>";
        return $list;
    }

    public function add_game() {
        $status = $this->user_model->validate_user();
        if ($status) {
            $type = $this->session->userdata('type');
            if ($type == 3) {
                $form = $this->get_add_game_page();
                $list = "";
                $list.="<br/><div class=''>";
                $list.="<form class='calc_form' method='post' enctype='multipart/form-data' id='add_game' action='" . $this->config->item('base_url') . "index.php/games/game_added'>";
                $list.= "<br>";
                $list.= "<table align='center' border='0' style='width: 100%;'>";
                $list.="<tr>";
                $list.= "<td>$form<td>";
                $list.= "</tr>";
                $list.= "</table><br>";
                $list.="</form>";
                $list.="</div>";
                return $list;
            } // end if $type==3
            else {
                redirect(base_url());
            }
        } // end if $status
        else {
            redirect(base_url());
        }
    }

    public function get_server_data($id) {
        $query = "select * from gameservers where gasID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $server = new stdClass();
        }
    }

    public function game_added($game) {
        $list = "";
        $icon = $game->icon;
        $tmp_name = $icon['files']['tmp_name'];
        $error = $icon['files']['error'];
        $size = $icon['files']['size'];
        $ext = 'jpg';
        if ($tmp_name != '' && $error == 0 && $size > 0) {
            $stamp = time();
            $rand = rand(12, 75);
            $new_file_name = $stamp . $rand . "." . $ext;
            $destination = $this->icon_path . "/" . $new_file_name;
            if (move_uploaded_file($tmp_name, $destination)) {
                $query = "insert into games "
                        . "(gamName,"
                        . "gamMoney,"
                        . "gamMoneys,"
                        . "gamMinCount,"
                        . "icon) "
                        . "values ('$game->name',"
                        . "'$game->currency',"
                        . "'$game->currency',"
                        . "$game->min_amount,"
                        . "'$new_file_name')";
                $this->db->query($query);
                $game_id = $this->db->insert_id();

                $query = "insert into content "
                        . "(cntTitle,"
                        . "cntBody, "
                        . "cntLanguageID,"
                        . "cntMetaTitle) "
                        . "values('$game->name',"
                        . "'Описание игры', "
                        . "2,"
                        . "'Купить $game->currency $game->name')";
                $this->db->query($query);
                $content_id = $this->db->insert_id();

                $query = "insert into gamescontent "
                        . "(gmcGameID,"
                        . "gmcContentID) "
                        . "values($game_id,"
                        . "$content_id)";
                $this->db->query($query);

                $list.="<br/><div class=''>";
                $list.="<form class='calc_form'>";
                $list.= "<br>";
                $list.= "<table align='center' border='0' style='width: 100%;'>";
                $list.="<tr>";
                $list.= "<td align='center'>&nbsp;&nbsp;<span>Игра успешно добавлена. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
                $list.= "</tr>";
                $list.= "</table><br>";
                $list.="</form>";
                $list.="</div>";
            } // end if move
            else {
                $list.="<br/><div class=''>";
                $list.="<form class='calc_form'>";
                $list.= "<br>";
                $list.= "<table align='center' border='0' style='width: 100%;'>";
                $list.="<tr>";
                $list.= "<td align='center'>&nbsp;&nbsp;<span>Ошибка загрузки. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
                $list.= "</tr>";
                $list.= "</table><br>";
                $list.="</form>";
                $list.="</div>";
            } // end else
        } // end if $tmp_name != '' && $error == 0 && $size > 0
        else {
            $list.="<br/><div class=''>";
            $list.="<form class='calc_form'>";
            $list.= "<br>";
            $list.= "<table align='center' border='0' style='width: 100%;'>";
            $list.="<tr>";
            $list.= "<td align='center'>&nbsp;&nbsp;<span>Ошибка загрузки. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
            $list.= "</tr>";
            $list.= "</table><br>";
            $list.="</form>";
            $list.="</div>";
        } // end else
        return $list;
    }

    public function delete_games($game_id) {
        $list = "";
        $query = "delete from games where gamID=$game_id";
        $this->db->query($query);
        $list.="<br/><div class=''>";
        $list.="<form class='calc_form'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td align='center'>&nbsp;&nbsp;<span>Игра удалена. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
        $list.= "</tr>";
        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function get_rate() {
        $query = "select * from exchange_rate";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $currency = new stdClass();
            $currency->euro_s = $row->eur_rate;
            $currency->rub_s = $row->rur_rate;
            $currency->usd_s = $row->usd_rate;
        }

        $currency_json = json_encode($currency);
        return $currency_json;
    }

    public function get_game_prices($id) {
        $list = "";
        $list.="<table border='0'>";
        $list.="";
        $list.="";
        $list.="";
        $list.="<tr>";
        $list.="<th style='padding:5px;'>Сервер</th><th style='padding:5px;'>Стоимость</th>";
        $list.="</tr>";
        $query = "select * from gameservers where gasGameID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<tr>";
            $list.="<td align='left' style='padding:5px;'>$row->gasName</td><td align='left' style='padding:5px;'>$$row->gasKurs</td>";
            $list.="</tr>";
        } // end foreach
        $list.="</table>";
        return $list;
    }

    public function get_game_action_text($id) {
        $list = "";
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $data = "<span id='action_text_$id'>" . $row->action_text . "</span>";
        }
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Акция</h4>
                </div>                
                <div class='modal-body'>                                
                
                <div class='container-fluid' style='text-align:left;'>
                <input type='hidden' id='id' value='$id'>
                <textarea name='body'>вфеф</textarea>                
                </div>            
                
                </div>
                
                <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel_edit_game'>Отмена</button></span>                
                </div>
        </div>
        </div>
        </div>";

        return $list;
    }

}
