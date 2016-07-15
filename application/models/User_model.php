<?php

class user_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('games_model');
        $this->config->load('email');
        $this->load->library('email');
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
                $list.="<option value='$server->exchangerate'>$server->name</option>";
            } // end foreach
        } // end if count($servers)>0
        $list.="</select>";
        return $list;
    }

    public function get_game_prices($id) {
        $list = $this->games_model->get_game_prices($id);
        return $list;
    }

    public function get_payment_methods() {
        $list = "";
        $list.="<select id='ptype' name='ptype' disabled>";
        $list.="<option value='0' selected>Выберите способ оплаты</option>";
        $query = "select * from payments where payActive=1 order by payName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='$row->payVal'>$row->payName</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_manager_games_list($email) {
        $list = "";
        $games = array();
        $list.="<select id='games' style='width:95px;'>";
        $list.="<option value='0' selected>Мои игры</option>";
        $query = "select * from users where email='$email'";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $userid = $row->id;
        }

        $query = "select * from manager2game where userid=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $game_data = $this->get_game_detailes2($row->gameid);
            $gameobj = new stdClass();
            $gameobj->id = $row->gameid;
            $gameobj->name = $game_data->name;
            $games[$game_data->name] = $gameobj;
        }
        ksort($games);
        foreach ($games as $game) {
            $list.="<option value='$game->id'>$game->name</option>";
        }
        $list.="</select>";
        return $list;
    }

    public function get_manager_orders($email, $status) {
        $list = "";

        if ($status == 1) {
            $list.="<select id='pending_orders' style='width:95px;'>";
            $list.="<option values='0' selected>Необработанные заказы</option>";
        }

        if ($status == 2) {
            $list.="<select id='processed_orders' style='width:95px;'>";
            $list.="<option values='0' selected>Обработанные заказы</option>";
        }

        $games_arr = array();
        $query = "select * from users where email='$email'";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $userid = $row->id;
        }

        $query = "select * from manager2game where userid=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $games_arr[] = $row->gameid;
        }

        $games_list = implode(',', $games_arr);
        $query = "select * from orders "
                . "where gameid in ($games_list) "
                . "and status=$status order by added desc";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $date = date('d-m-Y h:i:s', $row->added);
                $game = $this->get_game_detailes2($row->gameid);
                $list.="<option value='$row->id'>$row->nick $game->name $date</option>";
            } // end foreach
        } // end if $num > 0
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
                // It is partner
            } // end if $type==1
            else if ($type == 2) {
                // It is manager
                $email = $this->session->userdata('email');
                $firstname = $this->session->userdata('firstname');
                $lastname = $this->session->userdata('lastname');
                $pending_orders = $this->get_manager_orders($email, 1);
                $processed_orders = $this->get_manager_orders($email, 2);
                $games_list = $this->get_manager_games_list($email);

                $list.="<br/><div class=''>";
                $list.="<form class='calc_form'>";
                $list.= "<br>";
                $list.= "<table align='center' border='0' style='width: 100%;'>";

                $list.="<tr>";
                $list.= "<td>$pending_orders</td>";
                $list.= "<td>$processed_orders</td>";
                $list.= "<td>$games_list</td>";
                $list.= "</tr>";

                $list.="<tr>";
                $list.= "<td align='center' colspan='3'><span id='dashboard_container'></span></td>";
                $list.= "</tr>";

                $list.= "</table><br>";
                $list.="</form>";
                $list.="</div>";
            } // end if $type==2            
        } // end if $status
        else {
            if ($id > 0) {
                $detailes = $this->get_game_detailes($id); // array
                $game = $detailes['game'];
                $content = $detailes['content'];
                $servers = $this->get_game_servers($id);
                $ptype = $this->get_payment_methods();
                $prices = $this->get_game_prices($id);
                $list.="<br/><div class=''>";
                $list.="<div class='calc_form' id='add_order'>";
                $list.="<input type='hidden' id='gameid' value='$id'>";
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
                            <option value='1' selected>Купить $game->currency</option>                            
                            </select> <br> 
                            $servers <br>                            
                            $ptype <br> 
                                <table>
                            <tbody><tr>
                            <td id='calc_zoloto'>
                                <span>Получу:</span><br>
                                <input type='text' id='currency' name='currency' value='' class='inputsBorder' disabled>
                            </td>
                            <td id='calc_money'>
                                <span>Стоимость($):</span><br>
                                <input type='text' id='amount' name='amount' value='' class='inputsBorder' disabled>
                            </td>
                            </tr>
                            </tbody></table>
                            <div class='calc_min_sum_order'><small><strong>*Минимальная сумма заказа $<span class='min_sum_order_js'>$game->minamount</span><span class='CURRENCY_NAME'></span></strong></small></div>

                            <hr>
                            <div id='change_kurs'>
                            <div><strong>Цена:</strong></div>
                            <div><span id='count_money' class='red'>0</span> <span id='CURRENCY_NAME'>грн</span></div>
                            <div> за <span id='const_zoloto' class='red'>0</span> <span id='text_money'>$game->currency</span></div>
                            </div>
                            <hr>
                            
                                        
                            <div class='contactField'>
                            <label for='inp_phone'>Телефон*:</label>
                            <input type='text' class='optionalInput' required='required' inputsBorder' name='inp_phone' id='inp_phone' value='' data-validation='required'>
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
                            <div id='infoContact' class='calc_infoContact'>Можно заполнить одно поле из 2-х (skype или icq).</div>
                            </div>                     
                            
                            <div class='swap delivery_select' data-id-server='502'>
                            <select name='s_delivery' id='s_delivery' class='inputsBorder'>
                            <option value=''>Выберите способ доставки</option>
                            <option value='1' selected>Способ доставки на усмотрение оператора</option>
                            <option value='2'>Игровая почта</option>
                            </select>
                            </div>
                            
                            <div>
                                <div>
                                    <label for='inp_email'>Email*:</label>
                                    <input type='email' required='required' id='inp_email' name='inp_email' value='' class='inputsBorder' data-validation='email'>
                                </div>
                                
                                <div>
                                    <label for='inp_nickname'>Ник*:</label>
                                    <input type='text' required='required' id='inp_nickname' name='inp_nickname' value='' class='inputsBorder' data-validation='required'>
                                </div>
                                
                                <div>
                                    <label for='ta_comment' style='color:red;'>Комментарий:</label>
                                    <textarea name='ta_comment' id='ta_comment' class='inputsBorder'></textarea>
                                </div>

                            </div>
                                </div>                           
                                <div id='order_err'></div>
                                <div class='calc_order'>
                <button type='submit' class='calc_order_send' id='make_order'>Заказать</button>

                <div class='popup_visibility_visible popup popup_name_agreement popup_theme_ededed popup_autoclosable_yes popup_adaptive_yes popup_animate_yes agreement i-bem agreement_js_inited popup_js_inited popup_to_right' onclick='return {&quot;popup&quot;:{&quot;directions&quot;:{&quot;to&quot;:&quot;right&quot;}},&quot;agreement&quot;:{}};' style=': -17px; left: -300px;'>
                    <div class='popup__under'></div><i class='popup__tail' style='top: 24.98px;right:1px;'></i>
                    <div class='popup__content'>
                        Оформляя заказ, Вы принимаете <a target='_blank' href='/rules/' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>условия соглашения</a>.
                    </div>
                </div>
                        </div></form><br>                   
                        <div style='text-align:center;width:80%;margin:0 auto;'>$prices</div><br>
                            <div id='block2'>
                       <div class='swap'>$content->body</div>                       
                       </div>";

                $list.= "<br>";
                $list.= "<table align='center' border='0' style='width: 100%;'>";
                $list.="<tr>";
                $list.= "<td align center><td>";
                $list.= "</tr>";
                $list.= "</table><br>";
                $list.="</div>";
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

    public function get_games_list2() {
        $list = "";
        $list.="<select id='manager_games' name='manager_games[]' style='width:195px;' multiple>";
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
        $list.="<option value='add_user'><a href='" . $this->config->item('base_url') . "index.php/user/add_user' style='color: #000000;font-size: 14px;text-decoration: none;'>Добавить пользователя</a></option>";
        $list.="<option value='report'><a href='" . $this->config->item('base_url') . "index.php/user/report' style='color: #000000;font-size: 14px;text-decoration: none;'>Отчеты</a></option>";
        $list.="<option value='news'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/9719147' style='color: #000000;font-size: 14px;text-decoration: none;'>Новости</a></option>";
        $list.="<option value='buy'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/9719146' style='color: #000000;font-size: 14px;text-decoration: none;'>Как купить</a></option>";
        $list.="<option value='service'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/9719145' style='color: #000000;font-size: 14px;text-decoration: none;'>Услуги Гаранта</a></option>";
        $list.="<option value='supplier'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/9719143' style='color: #000000;font-size: 14px;text-decoration: none;'>Поставщикам</a></option>";
        $list.="<option value='guarantee'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/9719144' style='color: #000000;font-size: 14px;text-decoration: none;'>Гарантии</a></option>";
        $list.="<option value='contacts'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/3068' style='color: #000000;font-size: 14px;text-decoration: none;'>Контакты</a></option>";
        $list.="<option value='about'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/1' style='color: #000000;font-size: 14px;text-decoration: none;'>О нас</a></option>";
        $list.="<option value='exit'><a href='" . $this->config->item('base_url') . "index.php/user/logout' style='color: #000000;font-size: 14px;text-decoration: none;'>Выход</a></option>";
        $list.="</select>";
        return $list;
    }

    public function get_exit_page() {
        $list = "";
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' style='padding:15px;'>";
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

    public function get_user_role_name($i) {
        switch ($i) {
            case 1:
                $name = "Партнер";
                break;
            case 2:
                $name = "Менеджер";
                break;
            case 3:
                $name = "Админ";
                break;
            case 4:
                $name = "Модератор";
                break;
            case 5:
                $name = "Кассир";
                break;
        }
        return $name;
    }

    public function get_user_types($type) {
        $list = "";
        $list.="<select id='type' name='type' style='width:195px;'>";
        for ($i = 1; $i <= 5; $i++) {
            $name = $this->get_user_role_name($i);
            if ($i == $type) {
                $list.="<option value='$i' selected>$name</option>";
            } // end if $i==type
            else {
                $list.="<option value='$i'>$name</option>";
            } // end else
        }
        $list.="</select>";
        return $list;
    }

    public function get_manager_games($id) {
        $games = array();
        $list = "";
        $query = "select * from manager2game where userid=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $games[] = $row->gameid;
        }
        $list.="<select id='manager_games' name='manager_games[]' style='width:195px;' multiple>";
        $query = "select * from games order by gamName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            if (in_array($row->gamID, $games)) {
                $list.="<option value='$row->gamID' selected>$row->gamName</option>";
            } // end if in_array($row->gamID , $games)
            else {
                $list.="<option value='$row->gamID'>$row->gamName</option>";
            } // end else
        } // end foreach
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
        $games = $this->get_manager_games($user->id);
        $list.="<table style='' border='0' align='center'>";
        $users = $this->get_users_list();
        $list.="<tr>";
        $list.="<input type='hidden' id='id' name='id' value='$user->id'>";
        if ($user->type != 3) {
            $list.="<td>Пользователи</td><td align='left'>$users &nbsp;&nbsp;<a href='#' onClick='return false;' id='del_user' style='color: #000000;font-size: 14px;text-decoration: none;'>&nbsp;&nbsp;Удалить</a></td><td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>&nbsp;&nbsp;Меню</a></td>";
        } // end if $user->type!=3
        else {
            $list.="<td>Пользователи</td><td align='left'>$users</td><td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></td>";
        } // end else
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Фамилия*</td><td><input type='text' style='width:195px;' id='lastname' name='lastname' value='$user->lastname'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Имя*</td><td><input type='text' style='width:195px;' id='firstname' name='firstname' value='$user->firstname'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Email*</td><td><input type='text' style='width:195px;' id='email' name='email' value='$user->email' disabled></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Пароль*</td><td><input type='text' style='width:195px;' id='pwd' name='pwd' value='$user->pwd'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Телефон*</td><td><input type='text' style='width:195px;' id='phone' name='phone' value='$user->phone'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Адрес*</td><td><input type='text' style='width:195px;' id='addr' name='addr' value='$user->addr'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Skype</td><td><input type='text' style='width:195px;' id='skype' name='skype' value='$user->skype'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>ICQ</td><td><input type='text' style='width:195px;' id='icq' name='icq' value='$user->icq'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Тип</td><td align='left'>$type</td>";
        $list.="</tr>";

        if ($user->type == 2) {
            $list.="<tr>";
            $list.="<td>Игры</td><td align='left'>$games</td>";
            $list.="</tr>";
        } // end if $user->type==2

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
                $list.= "<td colspan='2'><span id='user_container'>$user</span><td>";
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

        // Update user entity
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

        if ($user->type == 2) {
            // Manager
            $query = "delete from manager2game where userid=$user->id";
            $this->db->query($query);
            if (count($user->games) > 0) {
                foreach ($user->games as $gameid) {
                    $query = "insert into manager2game "
                            . "(userid,gameid) values ($user->id,$gameid)";
                    $this->db->query($query);
                } // end foreacj
            } // end if count($user->game)>0
        } // end if $user->type == 2

        $list.="<br/><div class=''>";
        $list.="<form class='calc_form'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td align='center'>&nbsp;&nbsp;<span>Данные пользователя успешно обнолены. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
        $list.= "</tr>";
        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function report() {
        $list = "";
        $list.="<br/><div class=''>";
        $list.="<form class='calc_form'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td align='center'><div id='report_container'></div><td>";
        $list.= "</tr>";
        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function get_user_types2() {
        $list = "";
        $list.="<select id='user_type' name='user_type' style='width:195px;'>";
        for ($i = 1; $i <= 5; $i++) {
            switch ($i) {
                case 1:
                    $list.="<option value='$i' selected>Партнер</option>";
                    break;
                case 2:
                    $list.="<option value='$i'>Менеджер</option>";
                    break;
                case 4:
                    $list.="<option value='$i'>Модератор</option>";
                    break;
                case 5:
                    $list.="<option value='$i'>Касир</option>";
                    break;
            }
        }
        $list.="</select>";
        return $list;
    }

    public function get_user_type_name($i) {
        $list = "";
        switch ($i) {
            case 1:
                $list.="Партнер";
                break;
            case 2:
                $list.="Менеджер";
                break;
            case 4:
                $list.="Модератор";
                break;
            case 5:
                $list.="Касир";
                break;
        }
        return $list;
    }

    public function add_user() {
        $games = $this->get_games_list2();
        $types = $this->get_user_types2();
        $list = "";
        $list.="<br/><div class=''>";
        $list.="<form class='calc_form' id='add_manager'  method='post' action='" . $this->config->item('base_url') . "index.php/user/added_done'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' >";

        $list.="<tr>";
        $list.="<td align='left' style='padding:15px;'>Добавить пользователя</td><td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></td>";
        $list.="<tr>";

        $list.="<tr>";
        $list.= "<td align='left' style='padding:15px;'>Тип пользователя</td><td align='left'>$types</td>";
        $list.= "</tr>";

        $list.="<tr>";
        $list.= "<td align='left' style='padding:15px;'>Имя*</td><td align='left'><input type='text' id='firstname' name='firstname' style='width:195px;'></td>";
        $list.= "</tr>";

        $list.="<tr>";
        $list.= "<td align='left' style='padding:15px;'>Фамилия*</td><td align='left'><input type='text' id='lastname' name='lastname' style='width:195px;'></td>";
        $list.= "</tr>";

        $list.="<tr>";
        $list.= "<td align='left' style='padding:15px;'>Email*</td><td align='left'><input type='text' id='email' name='email' style='width:195px;'></td>";
        $list.= "</tr>";

        $list.="<tr>";
        $list.= "<td align='left' style='padding:15px;'>Телефон*</td><td align='left'><input type='text' id='phone' name='phone' style='width:195px;'></td>";
        $list.= "</tr>";

        $list.="<tr>";
        $list.= "<td align='left' style='padding:15px;'>Пароль*</td><td align='left'><input type='text' id='pwd' name='pwd' style='width:195px;'></td>";
        $list.= "</tr>";

        $list.="<tr>";
        $list.= "<td align='left' style='padding:15px;'>Адрес*</td><td align='left'><input type='text' id='address' name='address' style='width:195px;'></td>";
        $list.= "</tr>";

        $list.="<tr>";
        $list.= "<td align='left' style='padding:15px;'>Skype</td><td align='left'><input type='text' id='skype' name='skype' style='width:195px;'></td>";
        $list.= "</tr>";

        $list.="<tr>";
        $list.= "<td align='left' style='padding:15px;'>ICQ</td><td align='left'><input type='text' id='icq' name='icq' style='width:195px;'></td>";
        $list.= "</tr>";

        $list.="<tr id='manager_games' style='display:none;'>";
        $list.= "<td align='left' style='padding:15px;'>Игры*</td><td align='left'>$games</td>";
        $list.= "</tr>";


        $list.="<tr>";
        $list.= "<td align='center' colspan='2' style='padding:15px;'><span id='user_err'></span></td>";
        $list.= "</tr>";

        $list.="<tr>";
        $list.= "<td align='center' colspan='2'><button type='submit' class='calc_order_send'>Добавить</button></td>";
        $list.= "</tr>";

        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function user_added($user) {
        $list = "";
        $query = "insert into users "
                . "(firstname,"
                . "lastname,"
                . "email,"
                . "pwd,"
                . "phone,"
                . "addr,"
                . "skype,"
                . "icq,"
                . "type) "
                . "values(" . $this->db->escape($user->firstname) . ","
                . "" . $this->db->escape($user->lastname) . ","
                . "" . $this->db->escape($user->email) . ", "
                . "" . $this->db->escape($user->pwd) . ", "
                . "" . $this->db->escape($user->phone) . ", "
                . "" . $this->db->escape($user->addr) . ","
                . "" . $this->db->escape($user->skype) . ","
                . "" . $this->db->escape($user->icq) . ","
                . "" . $this->db->escape($user->type) . ")";
        $this->db->query($query);
        $id = $this->db->insert_id();

        if ($user->type == 2) {
            foreach ($user->games as $gameid) {
                $query = "insert into manager2game (userid, gameid) "
                        . "values($id,$gameid)";
                $this->db->query($query);
            } // end foreach
        } // end if $user->type==2
        $type_name = $this->get_user_type_name($user->type);
        $list.="<br/><div class=''>";
        $list.="<form class='calc_form'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td align='center'>&nbsp;&nbsp;<span>Новый $type_name успешно добавлен. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
        $list.= "</tr>";
        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function del_user($id) {
        $query = "delete from users where id=$id";
        $this->db->query($query);

        $query = "delete from manager2game where userid=$id";
        $this->db->query($query);
    }

    public function add_order($order) {
        $now = time();
        $query = "insert into orders "
                . "(gameid, nick, game_amount, email,"
                . "amount,"
                . "usd_amount,"
                . "currency,"
                . "phone,"
                . "skype,"
                . "icq,"
                . "delivery_way,"
                . "comment,"
                . "status,"
                . "added) "
                . "values('" . $order['gameid'] . "', "
                . "'" . $order['nick'] . "', "
                . "'" . $order['game_amount'] . "', "
                . "'" . $order['email'] . "',"
                . "'" . $order['amount'] . "',"
                . "'" . $order['usd_amount'] . "',"
                . "'" . $order['currency'] . "',"
                . "'" . $order['phone'] . "',"
                . "'" . $order['skype'] . "',"
                . "'" . $order['icq'] . "',"
                . "'" . $order['delivery_way'] . "',"
                . "'" . $order['comment'] . "',"
                . "'1',"
                . "'" . $now . "')";
        $this->db->query($query);
        $this->send_order_confirmation($order);

        $list = "";
        $list.= "<br><p>&nbsp;&nbsp;<span>Ваш заказ принят. Наш менеджер скоро с Вами свяжится.</span></p><br>";
        return $list;
    }

    public function get_game_detailes2($id) {
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $game = new stdClass();
            $game->id = $row->gamID;
            $game->name = $row->gamName;
            $game->currency = $row->gamMoneys;
        }
        return $game;
    }

    function get_delivery_method($id) {
        switch ($id) {
            case 1:
                $method = "Способ доставки на усмотрение оператора";
                break;
            case 2:
                $method = "Игровая почта";
                break;
        }
        return $method;
    }

    public function send_order_confirmation($order) {
        $game = $this->get_game_detailes2($order['gameid']);
        $method = $this->get_delivery_method($order['delivery_way']);
        $msg = "";
        $msg.= "<html>";
        $msg.="<body>";
        $msg.="<p align='center'>Уважаемый(я) " . $order['nick'] . "!</p>";
        $msg.="<p align='center'>Ваш заказ принят в обработку. Наш менеджер скоро с Вами свяжится</p>";

        $msg.="<table border='0' align='center'>";

        $msg.="<tr>";
        $msg.="<td>Название игры</td><td>$game->name</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td>Кол-во игровой валюты</td><td>" . $order['game_amount'] . " " . $game->currency . "</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td>Сумма к оплате</td><td>" . $order['amount'] . " " . $order['currency'] . "</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td>Способ доставки</td><td>$method</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td>Коментарий</td><td>" . $order['comment'] . "</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td colspan='2'><br><br>Если Вам нужна помощь, свяжитесь с нами по email <href='mailto:" . $this->config->item('smtp_user') . "'>" . $this->config->item('smtp_user') . "</a></td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td colspan='2'><br>С уважением,<br> Администрация сайта.</td>";
        $msg.="</tr>";

        $msg.="</table>";

        $msg.="</body>";
        $msg.="</html>";

        $this->email->from($this->config->item('smtp_user'), 'ECM-GAMES');
        $this->email->to($order['email']);
        $this->email->subject('ECM-GAMES Подтверждение заказа');
        $this->email->message($msg);
        $this->email->send();
    }

    public function get_status_dropdown($status) {
        $list = "";
        $list.="<select id='order_status'>";
        if ($status == 1) {
            $list.="<option value='1' selected>Необработанный</option>";
            $list.="<option value='2' >Обработанный</option>";
        } // end if $status==1
        if ($status == 2) {
            $list.="<option value='1'>Необработанный</option>";
            $list.="<option value='2' selected>Обработанный</option>";
        } // end i $status==2
        $list.="</select>";
        return $list;
    }

    public function get_payment_type_name($type) {
        $query = "select * from payment_types where id=$type";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $name = $row->type;
        }
        return $name;
    }

    public function get_order_status($id) {
        $query = "select * from orders where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $status = $row->status;
        }
        return $status;
    }

    public function get_client_payments_block($id) {
        $list = "";
        $status = $this->get_order_status($id);
        if ($status == 1) {
            $list.="<p align='center'><a onClick='return false;' id='addPayment' style='color:black;cursor:pointer;'>Добавить платеж</a></p>";
        } // end if $status==1
        $payments = array();
        $query = "select * from client_payments where order_id=$id";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $payment_id = $row->payment_id;
            } // end foreach

            $query = "select * from client_order_payments "
                    . "where id=$payment_id";
            $result = $this->db->query($query);
            foreach ($result->result() as $row) {
                $payment = new stdClass();
                $payment->user_data = $row->user_data;
                $payment->amount = $row->amount;
                $payment->currency = $row->currency;
                $payment->usd_amount = $row->usd_amount;
                $payment->currency = $row->currency;
                $payment->ptype = $row->ptype;
                $payment->comment = $row->comment;
                $payment->pdate = $row->pdate;
                $payments[] = $payment;
            } // end foreach
            foreach ($payments as $payment) {
                $payment_type = $this->get_payment_type_name($payment->ptype);
                $date = date('Y-m-d h:i:s', $payment->pdate);
                $list.="<table align='center' border='0'>";

                $list.="<tr>";
                $list.="<td style='padding:5px;'>Имя клиента</td><td style='padding:5px;'>$payment->user_data</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td style='padding:5px;'>Способ оплаты</td><td style='padding:5px;'>$payment_type</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td style='padding:5px;'>Оплаченная сумма</td><td style='padding:5px;'>$payment->amount ($payment->currency)</td>";
                $list.="</tr>";

                /*
                 * 
                  $list.="<tr>";
                  $list.="<td>Оплаченная сумма в USD</td><td>$payment->usd_amount</td>";
                  $list.="</tr>";
                 * 
                 */

                $list.="<tr>";
                $list.="<td style='padding:5px;'>Дополнительно</td><td style='padding:5px;'>$payment->comment</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td style='padding:5px;'>Дата платежа</td><td style='padding:5px;'>$date</td>";
                $list.="</tr>";

                $list.="</table>";

                $list.="<br><hr><br>";
            } // end foreach
        } // end if $num > 0
        else {
            $list.="<p align='center'>Нет оплаты</p>";
        }
        return $list;
    }

    public function get_order_details($id, $status) {
        $list = "";
        $order_status = $this->get_status_dropdown($status);
        $query = "select * from orders where id=$id and status=$status";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $game = $this->get_game_detailes2($row->gameid);
            $nick = $row->nick;
            $email = $row->email;
            $game_amount = $row->game_amount;
            $amount = $row->amount;
            $usd_amount = $row->usd_amount;
            $currency = $row->currency;
            $method = $this->get_delivery_method($row->delivery_way);
            $phone = $row->phone;
            $skype = $row->skype;
            $icq = $row->icq;
            $comment = $row->comment;
            $added = date('d-m-Y h:i:s', $row->added);
            $userid = $row->userid;
            $notes = $row->notes;
            $order_db_status = $row->status;
        } // end foreach
        if ($userid == 0) {
            // Assign order to manager
            $managerid = $this->session->userdata('id');
            $query = "update orders set userid=$managerid where id=$id";
            $this->db->query($query);
        } // end if $userid==0

        $payments = $this->get_client_payments_block($id);

        if ($order_db_status == 1) {
            $list.="<br><br><p align='center' style='font-weight:bold;' id='order_types'>Необработанные заказы</p>";
        }

        if ($order_db_status == 2) {
            $list.="<br><br><p align='center' style='font-weight:bold;' id='order_types'>Обработанные заказы</p>";
        }

        $list.="<p align='center' style='font-weight:bold;'>Детали заказа</p>";

        $list.="<br><table border='1' align='center' >";

        $list.="<input type='hidden' value='$id' id='order_id'>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Название игры</td><td style='padding: 15px;'>$game->name</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Ник заказчика</td><td style='padding: 15px;'>$nick</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Email заказчика</td><td style='padding: 15px;'>$email</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Телефон заказчика</td><td style='padding: 15px;'>$phone</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>ICQ заказчика</td><td style='padding: 15px;'>$icq</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Skype заказчика</td><td style='padding: 15px;'>$skype</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Кол-во игровой валюты</td><td style='padding: 15px;'>" . $game_amount . " " . $game->currency . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Сумма к оплате</td><td style='padding: 15px;'>" . $amount . " " . $currency . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Сумма в долларах</td><td style='padding: 15px;'>" . $usd_amount . "USD</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Способ доставки</td><td style='padding: 15px;'>$method</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Оплаты клиента</td><td style='padding: 15px;'><span id='client_payments'>$payments</span></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Коментарий</td><td style='padding: 15px;'>" . $comment . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Дата заказа</td><td style='padding: 15px;'>" . $added . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding: 15px;'>Приметки менеджера:</td><td style='padding: 15px;'><textarea rows='4' id='notes' cols='35'>$notes</textarea><br><span id='notes_err'></span></td>";
        $list.="</tr>";

        if ($order_db_status == 1) {
            $list.="<tr>";
            $list.="<td style='padding: 15px;'>Статус заказа</td><td style='padding: 15px;'>$order_status</td>";
            $list.="</tr>";
        } // end if $order_db_status==1

        return $list;
    }

    public function updta_order_notes($id, $notes) {
        $query = "update orders set notes='$notes' where id=$id";
        $this->db->query($query);
        $list = 'ok';
        return $list;
    }

    public function set_order_status($id, $status) {
        $query = "update orders set status=$status where id=$id";
        $this->db->query($query);
        $list = 'ok';
        return $list;
    }

    public function get_payment_types() {
        $list = "";
        $list.="<select id='ptype' style='width:175px;'>";
        $query = "select * from payment_types order by type";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            if ($row->id == 1) {
                $list.="<option value='$row->id' selected>$row->type</option>";
            } // end if $row->id==1
            else {
                $list.="<option value='$row->id'>$row->type</option>";
            } // end else
        }
        $list.="</select>";
        return $list;
    }

    public function get_add_payment_modal_box($id) {
        $list = "";
        $query = "select * from orders where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $currency = $row->currency;
        }

        $ptype = $this->get_payment_types();
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Добавить оплату</h4>
                </div>                
                <div class='modal-body'>                                
                
                <div class='container-fluid' style='text-align:left;'>
                <input type='hidden' id='id' value='$id'>          
                
                <table align='center'>
                
                <tr>
                <td style='padding:15px;'>Способ оплаты</span><td style='padding:15px;'>$ptype</td>
                </tr>

                <tr>
                <td style='padding:15px;'>Cумма ($currency)*</td><td style='padding:15px;'><input type='text' id='amount' style='width:175px;'></td>
                </tr>
                
                <tr>
                <td style='padding:15px;'>Коментарий</td><td style='padding:15px;'><textarea id='payment_comment'></textarea></td>
                </tr>
                
                <tr>
                <td colspan='2' style='padding:15px;'><span style='text-align:center' id='amount_err'></span></td>
                </tr>
                
                </table>               
                                
                </div>
                
                <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel_add_payment'>Отмена</button></span>
                <span align='center'><button type='button' class='btn btn-primary'  id='add_payment_btn'>Ок</button></span>
                </div>
        </div>
        </div>
        </div>";

        return $list;
    }

    public function get_order_details2($id) {
        $query = "select * from orders where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $order = new stdClass();
            $order->id = $id;
            $order->nick = $row->nick;
            $order->currency = $row->currency;
            // Do other stuff ...
        }
        return $order;
    }

    public function get_usd_amount($amount, $currency) {
        $query = "select * from exchange_rate";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $rates = new stdClass();
            $rates->eur = $row->eur_rate;
            $rates->rur = $row->rur_rate;
            $rates->usd = $row->usd_rate;
        }
        switch ($currency) {
            case 'eur':
                $rate = round(($rates->eur / $rates->usd), 2);
                break;
            case 'rur':
                $rate = round(($rates->rur / $rates->usd), 2);
                break;
            case 'usd':
                $rate = 1;
                break;
            case 'uah':
                $rate = round((1 / $rates->usd), 2);
                break;
        }
        $usd_amount = round(($amount) * $rate, 2);
        return $usd_amount;
    }

    public function add_payment($id, $amount, $comment, $ptype) {
        $list = "";
        $order = $this->get_order_details2($id);
        $usd_amount = $this->get_usd_amount($amount, $order->currency);
        $now = time();
        $query = "insert into client_order_payments "
                . "(orderid,"
                . "user_data,"
                . "amount,"
                . "usd_amount,"
                . "currency,"
                . "ptype,"
                . "comment,"
                . "pdate) "
                . "values($id,"
                . "'$order->nick',"
                . "'$amount',"
                . "'$usd_amount',"
                . "'$order->currency',"
                . "'$ptype', "
                . "'$comment',"
                . "'$now')";
        $this->db->query($query);
        $paymentid = $this->db->insert_id();

        $query = "insert into client_payments (order_id,payment_id) "
                . "values($id, $paymentid)";
        $this->db->query($query);

        $list.=$this->get_client_payments_block($id);
        return $list;
    }

    public function get_order_client_payment($id) {
        $query = "select * from client_payments where order_id=$id";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        return $num;
    }

}
