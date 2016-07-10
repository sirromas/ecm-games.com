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
                    $list.="<li><a href='" . $this->config->item('base_url') . "index.php/games/edit/$row->gamID' title='$row->gamName'>$row->gamName</a></li>";
                } // end if $type==3
                else {
                    $list.="<li><a href='" . $this->config->item('base_url') . "index.php/user/page/$type/$row->gamID' title='$row->gamName'>$row->gamName</a></li>";
                } // end else                
            } // end if $status 
            else {
                $list.="<li><a href='" . $this->config->item('base_url') . "index.php/user/page/0/$row->gamID' title='$row->gamName'>$row->gamName</a></li>";
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
                . "min_price='$game->min_price', "
                . "max_price='$game->max_price' "
                . "where gamID=$game->id";
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

    function xml2array_new($contents, $get_attributes = 1, $priority = 'tag') {
        if (!$contents)
            return array();

        if (!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);
        if (!$xml_values)
            return; //Hmm...	       

        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference
        //Go through the tags.
        $repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
        foreach ($xml_values as $data) {
            unset($attributes, $value); //Remove existing values, or there will be trouble
            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data); //We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if (isset($value)) {
                if ($priority == 'tag')
                    $result = $value;
                else {
                    if ($current[$tag][0]) {
                        $result['value'] = $value;
                    } else {
                        $result[0]['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
                    }
                }
            }

            //Set the attributes too.
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag')
                        $attributes_data[$attr] = $val;
                    else {
                        if ($current[$tag][0]) {
                            $result['attr'][$attr] = $val;
                        } else {
                            $result[0]['attr'][$attr] = $val; //Put the value in a assoc array if we are in the 'Attribute' mode
                        }
                    }
                    //else $result[0]['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if ($type == "open") {//The starting of the tag '<tag>'
                $parent[$level - 1] = &$current;
                if (!is_array($current) or ( !in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if ($attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;

                    $current = &$current[$tag];
                } else { //There was another element with the same tag name
                    if (isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level] ++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag], $result); //This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag . '_' . $level] = 2;

                        if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }
            } elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if (!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data)
                        $current[$tag . '_attr'] = $attributes_data;
                } else { //If taken, put all things inside a list(array)
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...
                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;

                        if ($priority == 'tag' and $get_attributes and $attributes_data) {

                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level] ++;
                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag], $result);
                        //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }

                            if ($attributes_data) {

                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level] ++; //0 and 1 index is already taken
                    }
                }
            } elseif ($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level - 1];
            }
        }

        return($xml_array);
    }

}
