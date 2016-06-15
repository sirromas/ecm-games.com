<?php

class user_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('games_model');
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

    public function get_game_detailes($id) {
        $content = $this->games_model->get_game_content($id);
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $game = new stdClass();
            $game->name = $row->gamName;
            $game->currency = $row->gamMoney;
            $game->minamount = $row->gamMinCount;
            $game->icon = $this->config->item('base_url') . 'assets/icon/' . $row->icon;
        }
        $data = array('game' => $game, 'content' => $content);
        return $data;
    }

    public function get_game_servers($id) {
        $list = "";
        $list.="<select id='server' name='server'>";
        $list.="<option value='0' selected>Сервер</option>";
        $servers = $this->games_model->get_game_servers($id);
        if (count($servers) > 0) {
            foreach ($servers as $server) {
                $list.="<option value='$server->id'>$server->name</option>";
            } // end foreach
        } // end if count($servers)>0
        $list.="</select>";
        return $list;
    }

    public function get_payment_methods() {
        $list = "";
        $list.="<select id='ptype' name='ptype'>";
        $list.="<option value='0' selected>Выберите способ оплаты</option>";
        $query = "select * from payments where payActive=1 order by payName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='$row->payID'>$row->payName</option>";
        }
        $list.="</select>";
        return $list;
    }

    public function get_user_dashboard($type) {
        $list = "";
        $id = $this->uri->segment(4);
        $status = $this->validate_user();
        if ($status) {
            if ($type == 3) {
                // It is admin user
                $games = $this->get_games_list();
                $deals = $this->get_deals_list();
                $users = $this->get_users_list();
                $other = $this->get_others_list();
                $list.="<br/><div class='calc'>";
                $list.="<form class='calc_form' >";
                $list.= "<br><br>";
                $list.= "<table align='center' border='0' style='width: 100%;'>";
                $list.="<tr>";
                $list.= "<td><span id='games_container'>$games</span><td>";
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
                // It is ordinary user
                if ($id > 0) {
                    $detailes = $this->get_game_detailes($id); // array
                    $game = $detailes['game'];
                    $content = $detailes['content'];
                    $servers = $this->get_game_servers($id);
                    $ptype=$this->get_payment_methods();
                    $list.="<br/><div class=''>";
                    $list.="<form class='calc_form' id='add_server'";
                    $list.="<h2 class='title'></h2>"
                            . "<div class='game-title'>
                        <img src='$game->icon' title='Купить $game->currency $game->name' alt='Купить $game->currency $game->name'>
                        <ul>              
                            <li><a href='#description' title='Об игре'>Об игре $game->name</a></li>
                            <li><a href='#video' title='Видео-обзор Lineage'>Видео-обзор $game->name</a></li>                                            
                        </ul>
                        </div>
                            <div id='block1'>
                            <select id='action' name='action'>    
                            <option value='0' selected>Я хочу ....</option>
                            <option value='1'>Купить $game->currency</option>
                            <option value='2'>Продать $game->currency</option>
                            </select> <br> 
                            $servers <br>                            
                            $ptype <br> 
                                <table>
                            <tbody><tr>
                            <td id='calc_zoloto'>
                                <span>Получу:</span><br>
                                <input type='text' id='currency' name='currency' value='' class='inputsBorder'>
                            </td>
                            <td id='calc_money'>
                                <span>Стоимость:</span><br>
                                <input type='text' id='money' name='imoney' value='' class='inputsBorder'>
                            </td>
                            </tr>
                            </tbody></table>
                            <div class='calc_min_sum_order'><small><strong>*Минимальная сумма заказа <span class='min_sum_order_js'>$game->minamount</span><span class='CURRENCY_NAME'>$</span></strong></small></div>

                            <hr>
                            <div id='change_kurs'>
                            <div><strong>Цена:</strong></div>
                            <div><span id='count_money' class='red'>0</span> <span id='CURRENCY_NAME'>грн</span></div>
                            <div> за <span id='const_zoloto' class='red'>0</span> <span id='text_money'>$game->currency</span></div>
                            </div>
                            <hr>
                            
                                        
                            <div class='contactField'>
                            <label for='inp_phone'>Телефон:</label>
                            <input type='text' class='optionalInput inputsBorder' name='inp_phone' id='inp_phone' value=''>
                            </div>
                            
                            <div class='contactField'>
                            <label for='inp_skype'>Skype:</label>
                            <input type='text' class='optionalInput inputsBorder' name='inp_skype' id='inp_skype' value=''>
                            </div>
                            
                            <div class='contactField'>
                            <label for='inp_icq'>ICQ:</label>
                            <input type='text' class='optionalInput inputsBorder' name='inp_icq' id='inp_icq' value=''>
                            </div>
                            
                            <div>
                            <div id='infoContact' class='calc_infoContact'>Можно заполнить одно поле из 3-х (телефон, skype или icq).</div>
                            </div>                     
                            
                            <div class='swap delivery_select' data-id-server='502'>
                            <select name='s_delivery' id='s_delivery' class='inputsBorder'>
                            <option value=''>Выберите способ доставки</option>
                            <option value='1'>Способ доставки на усмотрение оператора</option>
                            <option value='2'>Игровая почта</option>
                            </select>
                            </div>
                            
                            <div>
                                <div>
                                    <label for='inp_email'>Email:</label>
                                    <input type='email' required='required' id='inp_email' name='inp_email' value='' class='inputsBorder' data-validation='email'>
                                </div>
                                
                                <div>
                                    <label for='inp_nickname'>Ник:</label>
                                    <input type='text' required='required' id='inp_nickname' name='inp_nickname' value='' class='inputsBorder' data-validation='required'>
                                </div>
                                
                                <div>
                                    <label for='ta_comment'>Комментарий:</label>
                                    <textarea name='ta_comment' id='ta_comment' class='inputsBorder'></textarea>
                                </div>

                            </div>
                                </div>                           
                            
                                <div class='calc_order'>
                <button type='submit' class='calc_order_send'>Заказать</button>

                <div class='popup_visibility_visible popup popup_name_agreement popup_theme_ededed popup_autoclosable_yes popup_adaptive_yes popup_animate_yes agreement i-bem agreement_js_inited popup_js_inited popup_to_right' onclick='return {&quot;popup&quot;:{&quot;directions&quot;:{&quot;to&quot;:&quot;right&quot;}},&quot;agreement&quot;:{}};' style=': -17px; left: -300px;'>
                    <div class='popup__under'></div><i class='popup__tail' style='top: 24.98px;right:1px;'></i>
                    <div class='popup__content'>
                        Оформляя заказ, Вы принимаете <a target='_blank' style='color:#1D7485' href='/rules/'>условия соглашения</a>.
                    </div>
                </div>
                        </div><br>                   

                            <div id='block2'>
                       <div class='swap'>$content->body</div>                       
                       </div>";

                    $list.= "<br>";
                    $list.= "<table align='center' border='0' style='width: 100%;'>";
                    $list.="<tr>";
                    $list.= "<td align center><td>";
                    $list.= "</tr>";
                    $list.= "</table><br>";
                    $list.="</form>";
                    $list.="</div>";
                } // end if $id>0
                else {
                    $list.="<br/><div class=''>";
                    $list.="<form class='calc_form' id='add_server'";
                    $list.= "<br>";
                    $list.= "<table align='center' border='0' style='width: 100%;padding:25px;'>";
                    $list.="<tr>";
                    $list.= "<td align='right' style='padding:25px;'><button>Сделки</button></td><td align='left' style='padding:25px;'><button>Мои данные</button></td>";
                    $list.= "</tr>";
                    $list.= "</table><br>";
                    $list.="</form>";
                    $list.="</div>";
                } // end else
            } // end if $type==1
            else if ($type == 2) {
                // It is partner
            } // end if $type==2            
        } // end if $status
        else {
            if ($id > 0) {
                $detailes = $this->get_game_detailes($id); // array
                $game = $detailes['game'];
                $content = $detailes['content'];
                $list.="<br/><div class=''>";
                $list.="<form class='calc_form' id='add_server'";
                $list.="<h2 class='title'></h2>"
                        . "<div class='game-title'>
                        <img src='$game->icon' title='Купить $game->currency $game->name' alt='Купить $game->currency $game->name'>
                        <ul>              
                            <li><a href='#description' title='Об игре'>Об игре $game->name</a></li>
                            <li><a href='#video' title='Видео-обзор Lineage'>Видео-обзор $game->name</a></li>                                            
                        </ul>
                        </div>"
                        . "<div id='block1'>
                       <div class='swap'><div class='game-title'><ul><li><a href='" . $this->config->item('base_url') . "index.php/menu/page/login'>Вход</a></li></ul></div></div>                       
                       </div>"
                        . "<div id='block2'>
                       <div class='swap'>$content->body</div>                       
                       </div>";

                $list.= "<br>";
                $list.= "<table align='center' border='0' style='width: 100%;'>";
                $list.="<tr>";
                $list.= "<td align center><td>";
                $list.= "</tr>";
                $list.= "</table><br>";
                $list.="</form>";
                $list.="</div>";
            } // end if $id>0
            else {
                redirect(base_url());
            }
        } // end else
        return $list;
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
        $query = "select * from users order by firstname ";
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
        //$list.="<option value='add_user'><a href='" . $this->config->item('base_url') . "index.php/user/add_user' style='color: #000000;font-size: 14px;text-decoration: none;'>Добавить пользователя</a></option>";
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

    public function get_user_types($type) {
        $list = "";
        $list.="<select id='type' name='type'>";
        for ($i = 1; $i <= 3; $i++) {
            if ($i == $type) {
                $list.="<option value='$i' selected>$i</option>";
            } // end if $i==type
            else {
                $list.="<option value='$i'>$i</option>";
            } // end else
        }
        $list.="</select>";
        return $list;
    }

    public function get_user_edit_block($id) {
        $list = "";
        $query = "select * from users where id=$id";
        $result = $this->db->query($query);
        $user = new stdClass();
        foreach ($result->result() as $row) {
            $user->id = $id;
            $user->firstname = $row->firstname;
            $user->lastname = $row->lastname;
            $user->email = $row->email;
            $user->pwd = $row->pwd;
            $user->phone = $row->phone;
            $user->addr = $row->addr;
            $user->skype = $row->skype;
            $user->icq = $row->icq;
            $user->type = $row->type;
        }
        $type = $this->get_user_types($user->type);
        $list.="<table style='' border='0' align='center'>";
        $users = $this->get_users_list();
        $list.="<tr>";
        $list.="<input type='hidden' id='id' name='id' value='$user->id'>";
        $list.="<td>Пользователи</td><td align='left'>$users</td><td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Фамилия*</td><td><input type='text' id='lastname' name='lastname' value='$user->lastname'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Имя*</td><td><input type='text' id='firstname' name='firstname' value='$user->firstname'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Email*</td><td><input type='text' id='email' name='email' value='$user->email' disabled></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Пароль*</td><td><input type='text' id='pwd' name='pwd' value='$user->pwd'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Телефон*</td><td><input type='text' id='phone' name='phone' value='$user->phone'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Адрес*</td><td><input type='text' id='addr' name='addr' value='$user->addr'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Skype</td><td><input type='text' id='skype' name='skype' value='$user->skype'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>ICQ</td><td><input type='text' id='icq' name='icq' value='$user->icq'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Тип</td><td align='left'>$type</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td colspan='2'><span id='user_err'></span></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>&nbsp;</td><td><button>Ok</button></td>";
        $list.="</tr>";

        $list.="</table>";
        return $list;
    }

    public function get_edit_block($id) {
        $list = "";
        $status = $this->validate_user();
        if ($status) {
            $type = $this->session->userdata('type');
            if ($type == 3) {
                $user = $this->get_user_edit_block($id);
                $list.="<br/><div class='calc' style='text-align:center;'>";
                $list.="<form class='calc_form' id='update_user' method='post' action='" . $this->config->item('base_url') . "index.php/user/edit_done'>";
                $list.= "<br><br>";
                $list.= "<table align='center' border='0' style='width: 100%;'>";
                $list.="<tr>";
                $list.= "<td colspan='2'>$user<td>";
                $list.= "</tr>";
                $list.="</table><br><br>";
                $list.="<div style='text-align:center;' id='user_err'></div>";
                $list.="</form>";
                $list.="</div>";
                return $list;
            } // end if $type==3
        } // end of $status
        else {
            $this->session->sess_destroy();
            redirect(base_url());
        }
    }

    public function update_user($user) {
        $list = "";
        $query = "update users set "
                . "firstname=" . $this->db->escape($user->firstname) . ", "
                . "lastname=" . $this->db->escape($user->lastname) . ", "
                . "pwd=" . $this->db->escape($user->pwd) . ", "
                . "phone=" . $this->db->escape($user->phone) . ", "
                . "addr=" . $this->db->escape($user->addr) . ", "
                . "skype=" . $this->db->escape($user->skype) . ", "
                . "icq=" . $this->db->escape($user->icq) . ", "
                . "type=" . $this->db->escape($user->type) . " "
                . "where id=$user->id";
        $this->db->query($query);
        $list.="<br/><div class=''>";
        $list.="<form class='calc_form'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td>&nbsp;&nbsp;<span>Данные пользователя успешно обнолены. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
        $list.= "</tr>";
        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

}
