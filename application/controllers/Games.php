
<script src="http://mycodebusters.com/games/ckeditor/ckeditor.js"></script>

<?php

class Games extends CI_Controller {

    public $editor_path;
    public $games_page;

    public function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
        $this->load->model('login_model');
        $this->load->model('user_model');
        $this->load->model('games_model');
        $this->load->database();
        $this->editor_path = $this->config->item('base_url') . 'assets/editor/';
        $this->games_page = $this->config->item('base_url') . 'ajax/games.php';
    }

    public function get_common_elements() {
        $top_menu = $this->menu_model->get_top_menu();
        $games_list = $this->games_model->get_games_left_list();
        $data = array('top_menu' => $top_menu, 'games_list' => $games_list);
        return $data;
    }

    public function game_block() {
        $gameID = $this->uri->segment(3);
        $gameBlock = $this->games_model->game_block($gameID);
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

    public function get_games_edit_page($id) {
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
        $editor = $oFCKeditor->Create(false);
        $list.="<tr>";
        $list.="<td>Описание игры</td><td>" . $editor . "</td>";
        $list.="<tr>";

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
        $list.="<td><a href='#' onClik='return false;' id='update_game' style='color: #000000;font-size: 14px;text-decoration: none;'>Ok</a></td><td align='left'><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;'>Cancel</a></td>";
        $list.="<tr>";

        $list.="</table>";
        return $list;
    }

    public function edit() {
        $status = $this->user_model->validate_user();
        if ($status) {
            $type = $this->session->userdata('type');
            if ($type == 3) {
                $id = $this->uri->segment(3);
                $page = $this->games_model->edit($id);
                $common_data = $this->get_common_elements();
                $method_data = array('page' => $page);
                $data = array_merge($common_data, $method_data);
                $this->load->view('page_view', $data);
            } // end if $type == 3
        } // end if $status
        else {
            redirect(base_url());
        } // end else
    }

    public function get_game_modal_box() {
        $id = $this->input->post('id');
        $box = $this->games_model->get_game_content_box($id);
        echo $box;
    }

    public function save_game() {
        $game = new stdClass();
        $game->id = $this->input->post('id');
        $game->title = $this->input->post('title');
        $game->body = $this->input->post('body');
        $game->video = $this->input->post('video');
        $game->currency = $this->input->post('currency');
        $game->minamount = $this->input->post('minamount');
        $game->min_price = $this->input->post('min_price');
        $game->max_price = $this->input->post('max_price');
        $page = $this->games_model->update_game_content($game);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function update_game_content() {
        $game = new stdClass();
        $game->id = $this->input->post('id');
        $game->body = $this->input->post('body');
        $this->games_model->update_game_content2($game);
    }

    public function add_game() {
        $page = $this->games_model->add_game();
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function game_added() {
        $game = new stdClass();
        $game->icon = $_FILES;
        $game->name = $this->input->post('name');
        $game->currency = $this->input->post('currency');
        $game->min_amount = $this->input->post('min_amount');
        $page = $this->games_model->game_added($game);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function delete() {
        $game_id = $this->uri->segment(3);
        $page = $this->games_model->delete_games($game_id);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function rate() {
        $currency = $this->games_model->get_rate();
        echo $currency;
    }   
    

}
