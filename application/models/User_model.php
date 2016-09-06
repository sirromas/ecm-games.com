<?php

class user_model extends CI_Model {

    public $path;
    public $revenue_total;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('games_model');
        $this->load->model('menu_model');
        $this->config->load('email');
        $this->load->library('email');
        $this->path = $_SERVER ['DOCUMENT_ROOT'] . "/games/tmp";
        $this->revenue_total = 0;
    }

    public function validate_user() {
        $firstname = $this->session->userdata('firstname');
        $lastname = $this->session->userdata('lastname');
        $email = $this->session->userdata('email');
        if ($firstname != '' && $lastname != '' && $email != '') {
            return true;
        }  // end if $firstname!='' && $lastname!='' && $email!=''
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
                $userdata = array(
                    'id' => $row->id,
                    'firstname' => $row->firstname,
                    'lastname' => $row->lastname,
                    'email' => $row->email,
                    'type' => $row->type
                );
                $this->session->set_userdata($userdata);
                return $userdata ['type'];
            } // end foreach
        }  // end if $num > 0
        else {
            return false;
        }
    }

    public function get_game_detailes($id) {
        $content = $this->games_model->get_game_content($id);
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $game = new stdClass ();
            $game->name = $row->gamName;
            $game->currency = $row->gamMoney;
            $game->minamount = $row->gamMinCount;
            $game->icon = $this->config->item('base_url') . 'assets/icon/' . $row->icon;
        }
        $data = array(
            'game' => $game,
            'content' => $content
        );
        return $data;
    }

    public function get_game_servers($id) {
        $list = "";
        $list .= "<select id='server' name='server'>";
        $list .= "<option value='0' selected>Сервер</option>";
        $servers = array();
        $query = "select * from gameservers where gasGameID=$id and gasKurs>0";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $server = new stdClass ();
            $server->id = $row->gasID;
            $server->name = $row->gasName;
            $server->exchangerate = $row->gasKurs;
            $server->amount = $row->gasAmount;
            $server->qty = $row->gasQuantity;
            $servers [] = $server;
        }
        if (count($servers) > 0) {
            foreach ($servers as $server) {
                $list .= "<option value='" . $server->id . "_" . $server->exchangerate . "_" . $server->amount . "_" . $server->qty . "'>$server->name</option>";
            } // end foreach
        } // end if count($servers)>0
        $list .= "</select>";
        return $list;
    }

    public function get_game_prices($id) {
        $list = $this->games_model->get_game_prices($id);
        return $list;
    }

    public function get_payment_methods() {
        $list = "";
        $list .= "<select id='ptype' name='ptype' disabled>";
        $list .= "<option value='0' selected>Выберите способ оплаты</option>";
        $query = "select * from payments where payActive=1 order by payName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list .= "<option value='$row->payVal'>$row->payName</option>";
        }
        $list .= "</select>";
        return $list;
    }

    function get_manager_games_list($email) {
        $list = "";
        $games = array();
        $list .= "<select id='games' style='width:95px;'>";
        $list .= "<option value='0' selected>Мои игры</option>";
        $query = "select * from users where email='$email'";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $userid = $row->id;
        }

        $query = "select * from manager2game where userid=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $game_data = $this->get_game_detailes2($row->gameid);
            $gameobj = new stdClass ();
            $gameobj->id = $row->gameid;
            $gameobj->name = $game_data->name;
            $games [$game_data->name] = $gameobj;
        }
        ksort($games);
        foreach ($games as $game) {
            $list .= "<option value='$game->id'>$game->name</option>";
        }
        $list .= "</select>";
        return $list;
    }

    public function get_manager_orders($email, $status) {
        $list = "";

        if ($status == 1) {
            $list .= "<select id='pending_orders' style='width:95px;'>";
            $list .= "<option values='0' selected>Необработанные заказы</option>";
        }

        if ($status == 2) {
            $list .= "<select id='processed_orders' style='width:95px;'>";
            $list .= "<option values='0' selected>Обрабатываются</option>";
        }

        if ($status == 3) {
            $list .= "<select id='money_received' style='width:95px;'>";
            $list .= "<option values='0' selected>Получены деньги от клиента</option>";
        }

        if ($status == 4) {
            $list .= "<select id='money_sent' style='width:95px;'>";
            $list .= "<option values='0' selected>Отданы клиенту</option>";
        }

        if ($status == 5) {
            $list .= "<select id='supplier_paid' style='width:95px;'>";
            $list .= "<option values='0' selected>Оплачены поставщику</option>";
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
            $games_arr [] = $row->gameid;
        }

        $games_list = implode(',', $games_arr);
        $query = "select * from orders " . "where gameid in ($games_list) " . "and status=$status order by added desc";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $date = date('d-m-Y h:i:s', $row->added);
                $game = $this->get_game_detailes2($row->gameid);
                $list .= "<option value='$row->id'>$row->nick $game->name $date</option>";
            } // end foreach
        } // end if $num > 0
        $list .= "</select>";
        return $list;
    }

    public function get_cashier_orders() {
        $list = "";
        $list .= "<select id='orders' style='width:100px;'>";
        $list .= "<option selected value='1'>Неоплаченные</option>";
        $list .= "<option  value='2'>Оплаченные</option>";
        $list .= "</select>";
        return $list;
    }

    public function get_user_discount($email) {
        $query = "select * from users_discount where email='$email'";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $usd_amount = $row->usd_amount;
            }
            $discount = $this->get_dicount_size($usd_amount);
        } else {
            $discount = 0;
        }
        return $discount;
    }

    public function get_order_page($id) {
        $list = "";
        $detailes = $this->get_game_detailes($id); // array
        $game = $detailes ['game'];
        $content = $detailes ['content'];
        $servers = $this->get_game_servers($id);
        $ptype = $this->get_payment_methods();
        $prices = $this->get_game_prices($id);
        $list .= "<br/><div class=''>";
        $list .= "<div class='calc_form' id='add_order'>";
        $list .= "<input type='hidden' id='gameid' value='$id'>";
        $list .= "<h2 class='title'></h2>" . "<div class='game-title'>
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
		<span>Получу: &nbsp;<span id='zol_qty'></span><br>
		<input type='text' id='currency' name='currency' value='' class='inputsBorder' disabled>
		</td>
		<td id='calc_money'>
		<span>Стоимость:&nbsp;<span id='real_currency'></span></span><br>
		<input type='text' id='amount' name='amount' value='' class='inputsBorder' disabled>
		</td>
		</tr>
		</tbody></table>
		
		
		<hr>
		<div id='change_kurs'>
		
		<div><strong>Цена</strong></div>
		<div><span id='count_money' class='red'>0</span> <span id='CURRENCY_NAME'>грн</span></div><br>
		
		<div><strong>Цена с учетом скидки:</strong></div>
		<div><span id='count_money2' class='red'>0</span> <span id='CURRENCY_NAME2'>грн</span></div>
		</div>
		<br><br><br>
		<hr>
		
		
		<table align='center' border='0'>
		
		<tr>
		<td align='left'>Телефон:</td>
		<td align='left'><input type='text' class='optionalInput' required='required' inputsBorder' name='inp_phone' id='inp_phone' value='' data-validation='required'></td>
		</tr>
		
		
		<tr>
		<td align='left'>Skype:</td>
		<td align='left'><input type='text' class='optionalInput inputsBorder' name='inp_skype' id='inp_skype' value=''></td>
		</tr>
		
		<tr>
		<td align='left'>ICQ:</td>
		<td align='left'><input type='text' class='optionalInput inputsBorder' name='inp_icq' id='inp_icq' value=''></td>
		</tr>
		
		
		<tr>
		<td>&nbsp;</td>
		<td align='left'><select name='s_delivery' id='s_delivery' class='inputsBorder' style='width:152px;'>
				<option value=''>Выберите способ доставки</option>
				<option value='1' selected>Способ доставки на усмотрение оператора</option>
				<option value='2'>Игровая почта</option>
				<option value='3'>Встреча в игре</option>
				</select></td>
		</tr>
		
		<tr>
		<td align='left'>Email*:</td>
		<td align='left'><input type='email' required='required' id='inp_email' name='inp_email' value='' class='inputsBorder' data-validation='email'></td>
		</tr>
		
		<tr>
		<td align='left'>Ник*:</td>
		<td align='left'><input type='text' required='required' id='inp_nickname' name='inp_nickname' value='' class='inputsBorder' data-validation='required'></td>
		</tr>
		
		<tr>
		<td align='left'>Комментарий:</td>
		<td align='left'><textarea name='ta_comment' id='ta_comment' class='inputsBorder'></textarea></td>
		</tr>
		
		<tr>
		
		<td colspan='2'><div id='order_err'></div></td>
		</tr>
		
		<tr>
		<td>&nbsp;</td>
		<td align='left'><button type='submit' class='calc_order_send' id='make_order'>Заказать</button></td>
		</tr>
		
		</table>
		
		</div></form><br>
		
		
		<div style='text-align:center;width:100%;margin:0 auto;'>$prices</div><br>
		<div id='block2'>
		<div class='swap'>$content->body</div>
		</div>";

        $list .= "<br>";
        $list .= "<table align='center' border='0' style='width: 100%;'>";
        $list .= "<tr>";
        $list .= "<td align center><td>";
        $list .= "</tr>";
        $list .= "</table><br>";
        $list .= "</div>";
        $list .= "</div>";
        return $list;
    }

    public function get_user_dashboard($type) {
        $list = "";
        $id = $this->uri->segment(4);
        $status = $this->validate_user();
        if ($status) {
            if ($type == 1) {
                // It is partner
                if ($id > 0) {
                    $list.=$this->get_order_page($id);
                } else {
                    $discount = $this->get_user_discount($this->session->userdata('email'));
                    $list .= "<div class='calc'>";
                    $list .= "<form class='calc_form' >";
                    $list .= "<br><br>";
                    $list .= "<table align='center' border='0' style='width: 100%;'>";
                    $list .= "<tr>";
                    $list .= "<td align='center'><span id='games_container'>Ваша скидка <span style='font-weight:bold;'>$discount %</span></span><td>";
                    $list .= "</tr>";
                    $list .= "</table><br><br>";
                    $list .= "<div style='text-align:center;' id='forgot_err'></div>";
                    $list .= "</form>";
                    $list .= "</div>";
                }
            }  // end if $type==1
            else if ($type == 2) {
                // It is manager
                $email = $this->session->userdata('email');
                $firstname = $this->session->userdata('firstname');
                $lastname = $this->session->userdata('lastname');
                $pending_orders = $this->get_manager_orders($email, 1);
                $in_process = $this->get_manager_orders($email, 2);
                $client_received = $this->get_manager_orders($email, 3);
                $client_sent = $this->get_manager_orders($email, 4);
                $supplier_paid = $this->get_manager_orders($email, 5);
                $games_list = $this->get_manager_games_list($email);

                $list .= "<div class=''>";
                $list .= "<form class='calc_form'>";
                $list .= "<br>";
                $list .= "<table align='center' border='0' style='width: 100%;'>";

                $list .= "<tr>";
                $list .= "<td>$pending_orders</td>";
                $list .= "<td>$in_process</td>";
                $list .= "<td>$client_received </td>";
                $list .= "<td>$client_sent</td>";
                $list .= "<td>$supplier_paid </td>";
                $list .= "<td>$games_list</td>";
                $list .= "</tr>";

                $list .= "<tr>";
                $list .= "<td align='center' colspan='6'><span id='dashboard_container'></span></td>";
                $list .= "</tr>";

                $list .= "</table><br>";
                $list .= "</form>";
                $list .= "</div>";
            }  // end if $type==2
            else if ($type == 3) {
                // It is admin user
                $games = $this->get_games_list();
                $deals = $this->get_deals_list();
                $users = $this->get_users_list();
                $other = $this->get_others_list();
                $list .= "<div class='calc'>";
                $list .= "<form class='calc_form' >";
                $list .= "<table align='center' border='0' width='100%'>";
                $list .= "<tr>";
                $list .= "<td style='padding:15px;'><span id='games_container'>$games</span></td>";
                $list .= "<td style='padding:15px;'><span id='deals_container'>$deals</span></td>";
                $list .= "<td style='padding:15px;'><span id='user_container'>$users</span></td>";
                $list .= "<td style='padding:15px;'><span id='report_containers'>$other</span></td>";
                $list .= "</tr>";
                $list .= "</table>";
                $list .= "<div style='text-align:center;' class='row' id='forgot_err'></div>";
                $list .= "</form>";
                $list .= "</div>";
            }  // end if $type==3
            else if ($type == 4) {
                // It is moderator
                $item = 9719147;
                $news = $this->menu_model->get_admin_page($item);
                $list .= "<br/><div class='calc'>";
                $list .= "<form class='calc_form' >";
                $list .= "<br><br>";
                $list .= "<table align='center' border='0' style='width: 100%;'>";
                $list .= "<tr>";
                $list .= "<td><span id='games_container'>$news</span><td>";
                $list .= "</tr>";
                $list .= "</table><br><br>";
                $list .= "<div style='text-align:center;' id='forgot_err'></div>";
                $list .= "</form>";
                $list .= "</div>";
            }  // end if $type == 4
            else if ($type == 5) {
                // Cashier
                $orders = $this->get_cashier_orders();
                $list .= "<div class=''>";
                $list .= "<form class='calc_form'>";
                $list .= "<br>";
                $list .= "<table align='center' border='0' style='width: 100%;'>";

                $list .= "<tr>";
                $list .= "<td>$orders</td><td>Дата:*</td><td><input type='text' style='width:75px;' id='start'></td><td>Дата:*</td><td><input type='text' style='width:75px;' id='end'></td><td align='left'><a href='#' id='get_cashier_orders' style='color:black' onClick='return false;'>OK</a></td>";
                $list .= "</tr>";

                $list .= "<tr>";
                $list .= "<td align='center' colspan='6'><span id='ajax_loader' style='display:none;'><img src='/games/assets/images/ajax.gif' width='32' height='32' /></span></td>";
                $list .= "</tr>";

                $list .= "<tr>";
                $list .= "<td align='center' colspan='6'><span id='dashboard_container'></span></td>";
                $list .= "</tr>";

                $list .= "<tr>";
                $list .= "<td align='center' colspan='6'><span id='orders_err'></span></td>";
                $list .= "</tr>";

                $list .= "</table><br>";
                $list .= "</form>";
                $list .= "</div>";
            } // end if
        }  // end if $status
        else {
            if ($id > 0) {
                $list.=$this->get_order_page($id);
            }  // end if $id>0
            else {
                redirect(base_url());
            }
        } // end else
        return $list;
    }

    public function get_games_list() {
        $list = "";
        $list .= "<select id='games' style='width:95px;'>";
        $list .= "<option value='0' selected>Игры</option>";
        $query = "select * from games order by gamName";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $list .= "<option value='$row->gamID' >$row->gamName</option>";
            } // end foreach
        } // end if $num > 0
        $list .= "</select>";
        return $list;
    }

    public function get_games_list2() {
        $list = "";
        $list .= "<select id='manager_games' name='manager_games[]' style='width:195px;' multiple>";
        $query = "select * from games order by gamName";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $list .= "<option value='$row->gamID' >$row->gamName</option>";
            } // end foreach
        } // end if $num > 0
        $list .= "</select>";
        return $list;
    }

    public function get_revenue_games() {
        $list = "";
        $list .= "<select id='revgames' style='width:95px;'>";
        $list .= "<option value='0' selected>Игры</option>";
        $query = "select * from games order by gamName";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $list .= "<option value='$row->gamID' >$row->gamName</option>";
            } // end foreach
        } // end if $num > 0
        $list .= "</select>";
        return $list;
    }

    public function get_deals_list() {
        $list = "";
        $list .= "<select id='deals' style='width:95px;'>";
        $list .= "<option value='0' selected>Отчеты</option>";
        // $list.="<option value='deals'>Заказы</option>";
        $list .= "<option value='revenue'>Прибыль</option>";
        $list .= "</select>";
        return $list;
    }

    public function get_users_list() {
        $list = "";
        $list .= "<select id='user_types' style='width:95px;'>";
        $list .= "<option value='0' selected>Пользователи</option>";
        $list .= "<option value='3' >Администраторы</option>";
        $list .= "<option value='2' >Менеджеры</option>";
        $list .= "<option value='4' >Модераторы</option>";
        $list .= "<option value='5' >Касиры</option>";
        $list .= "<option value='1' >Партнеры</option>";
        $list .= "</select>";
        return $list;
    }

    public function get_users_list2() {
        $list = "";
        $list .= "<select id='users' style='width:95px;'>";
        $list .= "<option value='0' selected>Пользователи</option>";
        $query = "select * from users order by firstname ";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $list .= "<option value='$row->id' >$row->firstname $row->lastname</option>";
            } // end foreach
        } // end if $num > 0
        $list .= "</select>";
        return $list;
    }

    public function get_servers_list() {
        $list = "";
        $list .= "<select id='servers' style='width:95px;'>";
        $list .= "<option value='0' selected>Сервера</option>";
        $query = "select * from gameservers order by gasName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list .= "<option value='$row->gasID'>$row->gasName</option>";
        }
        $list .= "</select>";
        return $list;
    }

    public function get_others_list() {
        $list = "";
        $list .= "<select id='other' style='width:95px;'>";
        $list .= "<option value='0' selected>Другое</option>";
        $list .= "<option value='add_game'><a href='" . $this->config->item('base_url') . "index.php/games/add_game' style='color: #000000;font-size: 14px;text-decoration: none;'>Добавить игру</a></option>";
        $list .= "<option value='manager_game'><a href='" . $this->config->item('base_url') . "index.php/games/manager_game' style='color: #000000;font-size: 14px;text-decoration: none;'>Привязки игр</a></option>";
        $list .= "<option value='add_server'><a href='" . $this->config->item('base_url') . "index.php/games/add_server' style='color: #000000;font-size: 14px;text-decoration: none;'>Добавить сервер</a></option>";
        $list .= "<option value='add_user'><a href='" . $this->config->item('base_url') . "index.php/user/add_user' style='color: #000000;font-size: 14px;text-decoration: none;'>Добавить пользователя</a></option>";
        $list .= "<option value='exchange_rate'><a href='" . $this->config->item('base_url') . "index.php/user/exchange_rate' style='color: #000000;font-size: 14px;text-decoration: none;'>Курсы валют</a></option>";
        $list .= "<option value='news'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/9719147' style='color: #000000;font-size: 14px;text-decoration: none;'>Новости</a></option>";
        // $list.="<option value='buy'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/9719146' style='color: #000000;font-size: 14px;text-decoration: none;'>Как купить</a></option>";
        // $list.="<option value='service'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/9719145' style='color: #000000;font-size: 14px;text-decoration: none;'>Услуги Гаранта</a></option>";
        // $list.="<option value='supplier'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/9719143' style='color: #000000;font-size: 14px;text-decoration: none;'>Поставщикам</a></option>";
        // $list.="<option value='guarantee'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/9719144' style='color: #000000;font-size: 14px;text-decoration: none;'>Гарантии</a></option>";
        // $list.="<option value='contacts'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/3068' style='color: #000000;font-size: 14px;text-decoration: none;'>Контакты</a></option>";
        // $list.="<option value='about'><a href='" . $this->config->item('base_url') . "index.php/menu/adminpage/1' style='color: #000000;font-size: 14px;text-decoration: none;'>О нас</a></option>";

        $list .= "</select>";
        return $list;
    }

    public function get_exit_page() {
        $list = "";
        $list .= "<br/><div class='calc'>";
        $list .= "<form class='calc_form' style='padding:15px;'>";
        $list .= "<br><br>";
        $list .= "<table align='center' border='0' style='width: 100%;'>";
        $list .= "<input type='hidden' id='type' value='" . $this->session->userdata('type') . "'>";
        $list .= "<tr>";
        $list .= "<td colspan='2'>Выити из системы?&nbsp;&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-xs' id='logout'>Да</button> &nbsp;<button type='button' class='btn btn-primary btn-xs' id='cancel_logout' >Нет</button><td>";
        $list .= "</tr>";
        $list .= "</table><br><br>";
        $list .= "<div style='text-align:center;' id='forgot_err'></div>";
        $list .= "</form>";
        $list .= "</div>";
        return $list;
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url());
    }

    public function get_user_role_name($i) {
        switch ($i) {
            case 1 :
                $name = "Партнер";
                break;
            case 2 :
                $name = "Менеджер";
                break;
            case 3 :
                $name = "Админ";
                break;
            case 4 :
                $name = "Модератор";
                break;
            case 5 :
                $name = "Кассир";
                break;
        }
        return $name;
    }

    public function get_user_types($type) {
        $list = "";
        $list .= "<select id='type' name='type' style='width:195px;'>";
        for ($i = 1; $i <= 5; $i ++) {
            $name = $this->get_user_role_name($i);
            if ($i == $type) {
                $list .= "<option value='$i' selected>$name</option>";
            }  // end if $i==type
            else {
                $list .= "<option value='$i'>$name</option>";
            } // end else
        }
        $list .= "</select>";
        return $list;
    }

    public function get_manager_games($id) {
        $games = array();
        $list = "";
        $query = "select * from manager2game where userid=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $games [] = $row->gameid;
        }
        $list .= "<select id='manager_games' name='manager_games[]' style='width:195px;' multiple>";
        $query = "select * from games order by gamName";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            if (in_array($row->gamID, $games)) {
                $list .= "<option value='$row->gamID' selected>$row->gamName</option>";
            }  // end if in_array($row->gamID , $games)
            else {
                $list .= "<option value='$row->gamID'>$row->gamName</option>";
            } // end else
        } // end foreach
        $list .= "</select>";
        return $list;
    }

    public function get_user_edit_block($id) {
        $list = "";
        $query = "select * from users where id=$id";
        $result = $this->db->query($query);
        $user = new stdClass ();
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
        $list .= "<table style='' border='0' align='center'>";
        $users = $this->get_users_list();
        $list .= "<tr>";
        $list .= "<input type='hidden' id='id' name='id' value='$user->id'>";
        if ($user->type != 3) {
            $list .= "<td>Пользователи</td><td align='left'>$users &nbsp;&nbsp;<a href='#' onClick='return false;' id='del_user' style='color: #000000;font-size: 14px;text-decoration: none;'>&nbsp;&nbsp;Удалить</a></td><td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>&nbsp;&nbsp;Меню</a></td>";
        }  // end if $user->type!=3
        else {
            $list .= "<td>Пользователи</td><td align='left'>$users</td><td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></td>";
        } // end else
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td>Фамилия*</td><td><input type='text' style='width:195px;' id='lastname' name='lastname' value='$user->lastname'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td>Имя*</td><td><input type='text' style='width:195px;' id='firstname' name='firstname' value='$user->firstname'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td>Email*</td><td><input type='text' style='width:195px;' id='email' name='email' value='$user->email' disabled></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td>Пароль*</td><td><input type='text' style='width:195px;' id='pwd' name='pwd' value='$user->pwd'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td>Телефон*</td><td><input type='text' style='width:195px;' id='phone' name='phone' value='$user->phone'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td>Адрес*</td><td><input type='text' style='width:195px;' id='addr' name='addr' value='$user->addr'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td>Skype</td><td><input type='text' style='width:195px;' id='skype' name='skype' value='$user->skype'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td>ICQ</td><td><input type='text' style='width:195px;' id='icq' name='icq' value='$user->icq'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td>Тип</td><td align='left'>$type</td>";
        $list .= "</tr>";

        if ($user->type == 2) {
            $list .= "<tr>";
            $list .= "<td>Игры</td><td align='left'>$games</td>";
            $list .= "</tr>";
        } // end if $user->type==2

        $list .= "<tr>";
        $list .= "<td colspan='2'><span id='user_err'></span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td>&nbsp;</td><td><button>Ok</button></td>";
        $list .= "</tr>";

        $list .= "</table>";
        return $list;
    }

    public function get_edit_block($id) {
        $list = "";
        $status = $this->validate_user();
        if ($status) {
            $type = $this->session->userdata('type');
            if ($type == 3) {
                $user = $this->get_user_edit_block($id);
                $list .= "<div class='calc' style='text-align:center;'>";
                $list .= "<form class='calc_form' id='update_user' method='post' action='" . $this->config->item('base_url') . "index.php/user/edit_done'>";
                $list .= "<br><br>";
                $list .= "<table align='center' border='0' style='width: 100%;'>";
                $list .= "<tr>";
                $list .= "<td colspan='2'><span id='user_container'>$user</span><td>";
                $list .= "</tr>";
                $list .= "</table><br><br>";
                $list .= "<div style='text-align:center;' id='user_err'></div>";
                $list .= "</form>";
                $list .= "</div>";
                return $list;
            } // end if $type==3
        }  // end of $status
        else {
            $this->session->sess_destroy();
            redirect(base_url());
        }
    }

    public function update_user($user) {
        $list = "";

        // Update user entity
        $query = "update users set " . "firstname=" . $this->db->escape($user->firstname) . ", " . "lastname=" . $this->db->escape($user->lastname) . ", " . "pwd=" . $this->db->escape($user->pwd) . ", " . "phone=" . $this->db->escape($user->phone) . ", " . "addr=" . $this->db->escape($user->addr) . ", " . "skype=" . $this->db->escape($user->skype) . ", " . "icq=" . $this->db->escape($user->icq) . ", " . "type=" . $this->db->escape($user->type) . " " . "where id=$user->id";
        $this->db->query($query);

        if ($user->type == 2) {
            // Manager
            $query = "delete from manager2game where userid=$user->id";
            $this->db->query($query);
            if (count($user->games) > 0) {
                foreach ($user->games as $gameid) {
                    $query = "insert into manager2game " . "(userid,gameid) values ($user->id,$gameid)";
                    $this->db->query($query);
                } // end foreacj
            } // end if count($user->game)>0
        } // end if $user->type == 2

        $list .= "<br/><div class=''>";
        $list .= "<form class='calc_form'>";
        $list .= "<br>";
        $list .= "<table align='center' border='0' style='width: 100%;'>";
        $list .= "<tr>";
        $list .= "<td align='center'>&nbsp;&nbsp;<span>Данные пользователя успешно обнолены. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
        $list .= "</tr>";
        $list .= "</table><br>";
        $list .= "</form>";
        $list .= "</div>";
        return $list;
    }

    public function report() {
        $list = "";
        $list .= "<br/><div class=''>";
        $list .= "<form class='calc_form'>";
        $list .= "<br>";
        $list .= "<table align='center' border='0' style='width: 100%;'>";
        $list .= "<tr>";
        $list .= "<td align='center'><div id='report_container'></div><td>";
        $list .= "</tr>";
        $list .= "</table><br>";
        $list .= "</form>";
        $list .= "</div>";
        return $list;
    }

    public function get_user_types2() {
        $list = "";
        $list .= "<select id='user_type' name='user_type' style='width:195px;'>";
        for ($i = 1; $i <= 5; $i ++) {
            switch ($i) {
                case 1 :
                    $list .= "<option value='$i' selected>Партнер</option>";
                    break;
                case 2 :
                    $list .= "<option value='$i'>Менеджер</option>";
                    break;
                case 4 :
                    $list .= "<option value='$i'>Модератор</option>";
                    break;
                case 5 :
                    $list .= "<option value='$i'>Касир</option>";
                    break;
            }
        }
        $list .= "</select>";
        return $list;
    }

    public function get_user_type_name($i) {
        $list = "";
        switch ($i) {
            case 1 :
                $list .= "Партнер";
                break;
            case 2 :
                $list .= "Менеджер";
                break;
            case 4 :
                $list .= "Модератор";
                break;
            case 5 :
                $list .= "Касир";
                break;
        }
        return $list;
    }

    public function add_user() {
        $games = $this->get_games_list2();
        $types = $this->get_user_types2();
        $list = "";
        $list .= "<div class=''>";
        $list .= "<form class='calc_form' id='add_manager'  method='post' action='" . $this->config->item('base_url') . "index.php/user/added_done'>";
        $list .= "<br>";
        $list .= "<table align='center' border='0' >";

        $list .= "<tr>";
        $list .= "<td align='left' style='padding:15px;'>Добавить пользователя</td><td><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></td>";
        $list .= "<tr>";

        $list .= "<tr>";
        $list .= "<td align='left' style='padding:15px;'>Тип пользователя</td><td align='left'>$types</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='left' style='padding:15px;'>Имя*</td><td align='left'><input type='text' id='firstname' name='firstname' style='width:195px;'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='left' style='padding:15px;'>Фамилия*</td><td align='left'><input type='text' id='lastname' name='lastname' style='width:195px;'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='left' style='padding:15px;'>Email*</td><td align='left'><input type='text' id='email' name='email' style='width:195px;'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='left' style='padding:15px;'>Телефон*</td><td align='left'><input type='text' id='phone' name='phone' style='width:195px;'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='left' style='padding:15px;'>Пароль*</td><td align='left'><input type='text' id='pwd' name='pwd' style='width:195px;'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='left' style='padding:15px;'>Адрес*</td><td align='left'><input type='text' id='address' name='address' style='width:195px;'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='left' style='padding:15px;'>Skype</td><td align='left'><input type='text' id='skype' name='skype' style='width:195px;'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='left' style='padding:15px;'>ICQ</td><td align='left'><input type='text' id='icq' name='icq' style='width:195px;'></td>";
        $list .= "</tr>";

        $list .= "<tr id='manager_games' style='display:none;'>";
        $list .= "<td align='left' style='padding:15px;'>Игры*</td><td align='left'>$games</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center' colspan='2' style='padding:15px;'><span id='user_err'></span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center' colspan='2'><button type='submit' class='calc_order_send'>Добавить</button></td>";
        $list .= "</tr>";

        $list .= "</table><br>";
        $list .= "</form>";
        $list .= "</div>";
        return $list;
    }

    public function user_added($user) {
        $list = "";
        $query = "insert into users " . "(firstname," . "lastname," . "email," . "pwd," . "phone," . "addr," . "skype," . "icq," . "type) " . "values(" . $this->db->escape($user->firstname) . "," . "" . $this->db->escape($user->lastname) . "," . "" . $this->db->escape($user->email) . ", " . "" . $this->db->escape($user->pwd) . ", " . "" . $this->db->escape($user->phone) . ", " . "" . $this->db->escape($user->addr) . "," . "" . $this->db->escape($user->skype) . "," . "" . $this->db->escape($user->icq) . "," . "" . $this->db->escape($user->type) . ")";
        $this->db->query($query);
        $id = $this->db->insert_id();

        if ($user->type == 2) {
            foreach ($user->games as $gameid) {
                $query = "insert into manager2game (userid, gameid) " . "values($id,$gameid)";
                $this->db->query($query);
            } // end foreach
        } // end if $user->type==2
        $type_name = $this->get_user_type_name($user->type);
        $list .= "<br/><div class=''>";
        $list .= "<form class='calc_form'>";
        $list .= "<br>";
        $list .= "<table align='center' border='0' style='width: 100%;'>";
        $list .= "<tr>";
        $list .= "<td align='center'>&nbsp;&nbsp;<span>Новый $type_name успешно добавлен. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
        $list .= "</tr>";
        $list .= "</table><br>";
        $list .= "</form>";
        $list .= "</div>";
        return $list;
    }

    public function del_user($id) {
        $query = "delete from users where id=$id";
        $this->db->query($query);

        $query = "delete from manager2game where userid=$id";
        $this->db->query($query);
    }

    public function get_server_data($id) {
        $query = "select * from gameservers where gasID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $server = $row;
        }
        return $server;
    }

    public function get_order_no() {
        $query = "select * from orders " . "order by id desc limit 0,1";
        // echo "Order Query: ".$query."<br>";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $order = $row->order_no;
            } // end foreach
            $order_data = explode('-', $order);
            $order_no = $order_data [1];
            $order_no ++;
            $new_order_no = 'ECM-' . $order_no;
        }  // end if $num > 0
        else {
            $new_order_no = 'ECM-10001';
        } // end else
        // echo "New Order: ".$new_order_no."<br>";
        return $new_order_no;
    }

    public function update_users_discount($email, $amount, $currency) {
        $query = "select * from users where email='$email'";
        // echo "Order Query: ".$query."<br>";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            if ($currency != 'usd') {
                $usd_amount = $this->get_usd_amount($amount, $currency);
            }  // end if $currency!='usd'
            else {
                $usd_amount = $amount;
            }
            $query = "select * from users_discount where email='$email'";
            $result = $this->db->query($query);
            $num = $result->num_rows();
            if ($num > 0) {
                foreach ($result->result() as $row) {
                    $db_usd_amount = $row->usd_amount;
                } // end foreach
                $new_usd_amount = $db_usd_amount + $usd_amount;
                $query = "update users_discount " . "set usd_amount='$new_usd_amount' " . "where email='$email'";
            }  // end if $num>0
            else {
                $new_usd_amount = $usd_amount;
                $query = "insert into users_discount (email,usd_amount) " . "values('$email','$new_usd_amount')";
            }
            $this->db->query($query);
        } // end if $num > 0
    }

    public function add_order($order) {
        $now = time();
        $server_data = $this->get_server_data($order ['server']);
        // $total_game_currency = $order['game_amount'] . $server_data->gasAmount;
        $total_game_currency = $order ['game_amount'];
        $order_no = $this->get_order_no();
        $usd_amount = $this->get_usd_amount($order ['amount'], $order ['currency']);
        $query = "insert into orders " . "(gameid, order_no, serverid, nick, game_amount, email," . "amount," . "usd_amount," . "currency," . "phone," . "skype," . "icq," . "delivery_way," . "comment," . "status," . "added) " . "values('" . $order ['gameid'] . "', '" . $order_no . "' ," . $order ['server'] . ", " . "'" . $order ['nick'] . "', " . "'" . $total_game_currency . "', " . "'" . $order ['email'] . "'," . "'" . $order ['amount'] . "'," . "'" . $usd_amount . "'," . "'" . $order ['currency'] . "'," . "'" . $order ['phone'] . "'," . "'" . $order ['skype'] . "'," . "'" . $order ['icq'] . "'," . "'" . $order ['delivery_way'] . "'," . "'" . $order ['comment'] . "'," . "'1'," . "'" . $now . "')";
        $this->db->query($query);
        $this->update_users_discount($order ['email'], $order ['amount'], $order ['currency']);
        $order ['order_no'] = $order_no;
        $order ['game_amount'] = $total_game_currency;
        $this->send_order_confirmation($order);

        $list = "";
        $list .= "<br><p>&nbsp;&nbsp;<span>Ваш заказ принят. Наш менеджер скоро с Вами свяжится.</span></p><br>";
        return $list;
    }

    public function get_game_detailes2($id) {
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $game = new stdClass ();
            $game->id = $row->gamID;
            $game->name = $row->gamName;
            $game->currency = $row->gamMoneys;
            $game->min_price = $row->min_price;
            $game->max_price = $row->max_price;
        }
        return $game;
    }

    function get_delivery_method($id) {
        switch ($id) {
            case 1 :
                $method = "Способ доставки на усмотрение оператора";
                break;
            case 2 :
                $method = "Игровая почта";
                break;
            case 3 :
                $method = "Встреча в игре";
                break;
        }
        return $method;
    }

    public function send_order_confirmation($order) {
        $game = $this->get_game_detailes2($order ['gameid']);
        $method = $this->get_delivery_method($order ['delivery_way']);
        $msg = "";
        $msg .= "<html>";
        $msg .= "<body>";
        $msg .= "<p align='center'>Уважаемый(я) " . $order ['nick'] . "!</p>";
        $msg .= "<p align='center'>Ваш заказ # " . $order ['order_no'] . " принят  в обработку. Наш менеджер скоро с Вами свяжится</p>";

        $msg .= "<table border='0' align='center'>";

        $msg .= "<tr>";
        $msg .= "<td>Название игры</td><td>$game->name</td>";
        $msg .= "</tr>";

        $msg .= "<tr>";
        $server_currency_unity = $this->get_server_currency_unity($order ['server']);
        $msg .= "<td>Кол-во игровой валюты</td><td>" . $order ['game_amount'] . "&nbsp;&nbsp;" . $server_currency_unity['qty'] . $server_currency_unity['amount'] . "&nbsp;&nbsp;" . $game->currency . "</td>";
        $msg .= "</tr>";

        $msg .= "<tr>";
        $msg .= "<td>Сумма к оплате</td><td>" . $order ['amount'] . " " . $order ['currency'] . "</td>";
        $msg .= "</tr>";

        $msg .= "<tr>";
        $msg .= "<td>Способ доставки</td><td>$method</td>";
        $msg .= "</tr>";

        $msg .= "<tr>";
        $msg .= "<td>Коментарий</td><td>" . $order ['comment'] . "</td>";
        $msg .= "</tr>";

        $msg .= "<tr>";
        $msg .= "<td colspan='2'><br><br>Если Вам нужна помощь, свяжитесь с нами по email <href='mailto:" . $this->config->item('smtp_user') . "'>" . $this->config->item('smtp_user') . "</a></td>";
        $msg .= "</tr>";

        $msg .= "<tr>";
        $msg .= "<td colspan='2'><br>С уважением,<br> Администрация сайта.</td>";
        $msg .= "</tr>";

        $msg .= "</table>";

        $msg .= "</body>";
        $msg .= "</html>";

        $this->email->from($this->config->item('smtp_user'), 'ECM-GAMES');
        $this->email->to($order ['email']);
        $this->email->subject('ECM-GAMES Подтверждение заказа');
        $this->email->message($msg);
        $this->email->send();
    }

    public function get_status_dropdown($status) {
        $list = "";
        $list .= "<select id='order_status'>";
        if ($status == 1) {
            $list .= "<option value='1' selected>Необработанный</option>";
            $list .= "<option value='2' >Обрабатывается</option>";
        } // end if $status==1
        if ($status == 2) {
            $list .= "<option value='2' selected>Обрабатывается</option>";
            $list .= "<option value='3'>Получены деньги от клиента</option>";
        } // end i $status==2
        if ($status == 3) {
            $list .= "<option value='3' selected>Получены деньги от клиента</option>";
            $list .= "<option value='4'>Отданы клиенту</option>";
        } // end i $status==3
        if ($status == 4) {
            $list .= "<option value='4' selected>Отданы клиенту</option>";
            $list .= "<option value='5'>Оплачены поставщику</option>";
        } // end if $status==4

        $list .= "</select>";
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
            $list .= "<p align='center'><a onClick='return false;' id='addPayment' style='color:black;cursor:pointer;'>Добавить платеж</a></p>";
        } // end if $status==1
        $payments = array();
        $query = "select * from client_payments where order_id=$id";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $payment_id = $row->payment_id;
            } // end foreach

            $query = "select * from client_order_payments " . "where id=$payment_id";
            $result = $this->db->query($query);
            foreach ($result->result() as $row) {
                $payment = new stdClass ();
                $payment->user_data = $row->user_data;
                $payment->amount = $row->amount;
                $payment->currency = $row->currency;
                $payment->usd_amount = $row->usd_amount;
                $payment->currency = $row->currency;
                $payment->ptype = $row->ptype;
                $payment->comment = $row->comment;
                $payment->pdate = $row->pdate;
                $payments [] = $payment;
            } // end foreach
            foreach ($payments as $payment) {
                $payment_type = $this->get_payment_type_name($payment->ptype);
                $date = date('Y-m-d h:i:s', $payment->pdate);
                $list .= "<table align='center' border='0'>";

                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Имя клиента</td><td style='padding:5px;'>$payment->user_data</td>";
                $list .= "</tr>";

                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Способ оплаты</td><td style='padding:5px;'>$payment_type</td>";
                $list .= "</tr>";

                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Оплаченная сумма</td><td style='padding:5px;'>$payment->amount ($payment->currency)</td>";
                $list .= "</tr>";

                /*
                 *
                 * $list.="<tr>";
                 * $list.="<td>Оплаченная сумма в USD</td><td>$payment->usd_amount</td>";
                 * $list.="</tr>";
                 *
                 */

                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Дополнительно</td><td style='padding:5px;'>$payment->comment</td>";
                $list .= "</tr>";

                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Дата платежа</td><td style='padding:5px;'>$date</td>";
                $list .= "</tr>";

                $list .= "</table>";

                $list .= "<br><hr><br>";
            } // end foreach
        }  // end if $num > 0
        else {
            $list .= "<p align='center'>Нет оплаты</p>";
        }
        return $list;
    }

    public function get_order_server_block($serverid) {
        $list = "";
        $query = "select * from gameservers where gasID=$serverid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $server = $row;
        }
        $game = $this->get_game_detailes2($server->gasGameID);
        $list .= "<p align='center'>$server->gasName</p>";
        $list .= "<p align='center'>Цена за $server->gasQuantity$server->gasAmount ($game->currency) &nbsp; $$server->gasKurs</p>";
        return $list;
    }

    public function get_currencies_block() {
        $list = "";
        $list .= "<select id='currencies'>";
        $list .= "<option value='usd' selected>USD</option>";
        $list .= "<option value='uah'>UAH</option>";
        $list .= "<option value='eur'>EUR</option>";
        $list .= "<option value='rur'>RUR</option>";
        $list .= "</select>";
        return $list;
    }

    public function get_order_currency_price($id) {
        $payments = "";
        $query = "select * from orders where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $supplier_paid = $row->currency_price;
            $game = $this->get_game_detailes2($row->gameid);
        }

        if ($supplier_paid > 0) {
            $payments .= "<p align='center'>Мин цена ($): $game->min_price&nbsp;Макс цена ($): $game->max_price</p>";
            $payments .= "<p align='center'>$" . $supplier_paid . "</p>";
        }  // end if $supplier_paid > 0
        else {
            $payments .= "<p align='center'>Мин цена ($): $game->min_price&nbsp;Макс цена ($): $game->max_price</p>";
            $payments .= "<p align='center'>N/A &nbsp; <span id='add_sup_payment' style='cursor:pointer;font-weight:bold;'>Добавить</span></p>" . "<div id='supplier' style='display:none;text-align:center;'>$<input type='text' style='width:75px;' id='supp_amount'>&nbsp;<a id='add_supplier_payment2_btn' href='#' onClick='return false;' style='color:black;font-weight:bold;'>OK</a></div>";
        }
        return $payments;
    }

    public function get_order_details($id, $status, $manager = true) {
        $list = "";
        $payments = "";
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
            $server = $this->get_order_server_block($row->serverid);
            $server_currency_unity = $this->get_server_currency_unity($row->serverid);
            $notes = $row->notes;
            $order_db_status = $row->status;
        } // end foreach
        if ($manager == true) {
            if ($userid == 0) {
                // Assign order to manager
                $managerid = $this->session->userdata('id');
                $query = "update orders set userid=$managerid where id=$id";
                $this->db->query($query);
            } // end if $userid==0
        } // end fi $manager==true

        $payments .= $this->get_order_currency_price($id);

        if ($manager == true) {

            if ($order_db_status == 1) {
                $list .= "<br><br><p align='center' style='font-weight:bold;' id='order_types'>Необработанные заказы</p>";
            }

            if ($order_db_status == 2) {
                $list .= "<br><br><p align='center' style='font-weight:bold;' id='order_types'>В обработке</p>";
            }

            if ($order_db_status == 3) {
                $list .= "<br><br><p align='center' style='font-weight:bold;' id='order_types'>Получены деньги от клиента</p>";
            }

            if ($order_db_status == 4) {
                $list .= "<br><br><p align='center' style='font-weight:bold;' id='order_types'>Отданы клиенту</p>";
            }

            if ($order_db_status == 5) {
                $list .= "<br><br><p align='center' style='font-weight:bold;' id='order_types'>Оплачены поставщику</p>";
            }
        } // end if $manager == true

        $list .= "<p align='center' style='font-weight:bold;'>Детали заказа</p>";

        $list .= "<br><table border='1' align='center' >";

        $list .= "<input type='hidden' value='$id' id='order_id'>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Название игры</td><td style='padding: 15px;'>$game->name</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Ник заказчика</td><td style='padding: 15px;'>$nick</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Email заказчика</td><td style='padding: 15px;'>$email</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Телефон заказчика</td><td style='padding: 15px;'>$phone</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>ICQ заказчика</td><td style='padding: 15px;'>$icq</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Skype заказчика</td><td style='padding: 15px;'>$skype</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        // $server_currency_unity
        $list .= "<td style='padding: 15px;'>Кол-во игровой валюты</td><td style='padding: 15px;'>" . $game_amount . "&nbsp;" . $server_currency_unity['qty'] . $server_currency_unity['amount'] . "&nbsp;" . "(" . $game->currency . ")</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Сервер игры</td><td style='padding: 15px;'>$server</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Сумма к оплате</td><td style='padding: 15px;'>" . $amount . " " . $currency . " (&nbsp;$$usd_amount)</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Способ доставки</td><td style='padding: 15px;'>$method</td>";
        $list .= "</tr>";

        if ($manager == true) {
            $list .= "<tr>";
            $list .= "<td style='padding: 15px;'>Цена покупки валюты ($)</td><td style='padding: 15px;'><span id='currency_price'>$payments</span></td>";
            $list .= "</tr>";
        } // end if $manager == true

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Коментарий</td><td style='padding: 15px;'>" . $comment . "</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Дата заказа</td><td style='padding: 15px;'>" . $added . "</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Приметки менеджера:</td><td style='padding: 15px;'><textarea rows='4' id='notes' cols='35'>$notes</textarea><br><span id='notes_err'></span></td>";
        $list .= "</tr>";

        if ($manager == true) {
            // if ($order_db_status == 1) {
            $list .= "<tr>";
            $list .= "<td style='padding: 15px;'>Статус заказа</td><td style='padding: 15px;'>$order_status</td>";
            $list .= "</tr>";
            // } // end if $order_db_status==1
        } // end if $manager == true

        $list .= "</table>";

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
        $list .= "<select id='ptype' style='width:175px;'>";
        $query = "select * from payment_types order by type";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            if ($row->id == 1) {
                $list .= "<option value='$row->id' selected>$row->type</option>";
            }  // end if $row->id==1
            else {
                $list .= "<option value='$row->id'>$row->type</option>";
            } // end else
        }
        $list .= "</select>";
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
        $list .= "<div id='myModal' class='modal fade'>
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
                <td style='padding:15px;'>Способ оплаты*</span><td style='padding:15px;'>$ptype</td>
                </tr>

                <tr>
                <td style='padding:15px;'>Cумма ($currency)*</td><td style='padding:15px;'><input type='text' id='amount' style='width:175px;'></td>
                </tr>
                
                <tr>
                <td style='padding:15px;'>Коментарий</td><td style='padding:15px;'><textarea id='payment_comment' cols='26'></textarea></td>
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

    public function get_add_payment_modal_box2($id) {
        $list = "";
        $query = "select * from orders where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $currency = $row->currency;
        }

        $ptype = $this->get_payment_types();
        $list .= "<div id='myModal' class='modal fade'>
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
                <td style='padding:15px;'>Данные поставщика*</span><td style='padding:15px;'><input type='text' id='supplier_data' style='width:175px;'></td>
                </tr>
                
                <tr>
                <td style='padding:15px;'>Способ оплаты*</span><td style='padding:15px;'>$ptype</td>
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
                <span align='center'><button type='button' class='btn btn-primary'  id='add_payment_supplier_btn'>Ок</button></span>
                </div>
        </div>
        </div>
        </div>";

        return $list;
    }

    public function add_supplier_payment($id, $amount, $comment, $ptype, $supplier_data) {
        $order = $this->get_order_details2($id);
        $usd_amount = $this->get_usd_amount($amount, $order->currency);
        $now = time();
        $query = "insert into supplier_order_payments " . "(orderid," . "supplier_data," . "amount," . "usd_amount," . "currency," . "ptype," . "comment," . "pdate) " . "values($id," . "'$supplier_data'," . "'$amount'," . "'$usd_amount'," . "'$order->currency'," . "'$ptype', " . "'$comment'," . "'$now')";
        $this->db->query($query);
        $paymentid = $this->db->insert_id();

        $query = "insert into supplier_payments (order_id,payment_id) " . "values($id, $paymentid)";
        $this->db->query($query);
    }

    public function get_order_details2($id) {
        $query = "select * from orders where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $order = $row;
        }
        return $order;
    }

    public function get_usd_amount($amount, $currency) {
        $query = "select * from exchange_rate";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $rates = new stdClass ();
            $rates->eur = $row->eur_rate;
            $rates->rur = $row->rur_rate;
            $rates->usd = $row->usd_rate;
        }
        // /echo "Currency: " . $currency . "<br>";
        switch ($currency) {
            case 'eur' :
                $rate = round(($rates->usd / $rates->eur), 2);
                break;
            case 'rur' :
                $rate = round(($rates->usd / $rates->rur), 2);
                break;
            case 'usd' :
                $rate = 1;
                break;
            case 'uah' :
                $rate = round(($rates->usd), 2);
                break;
        }
        $usd_amount = round(($amount / $rate), 2);
        return $usd_amount;
    }

    public function add_payment($id, $amount, $comment, $ptype) {
        $list = "";
        $order = $this->get_order_details2($id);
        $usd_amount = $this->get_usd_amount($amount, $order->currency);
        $now = time();
        $query = "insert into client_order_payments " . "(orderid," . "user_data," . "amount," . "usd_amount," . "currency," . "ptype," . "comment," . "pdate) " . "values($id," . "'$order->nick'," . "'$amount'," . "'$usd_amount'," . "'$order->currency'," . "'$ptype', " . "'$comment'," . "'$now')";
        $this->db->query($query);
        $paymentid = $this->db->insert_id();

        $query = "insert into client_payments (order_id,payment_id) " . "values($id, $paymentid)";
        $this->db->query($query);

        $list .= $this->get_client_payments_block($id);
        return $list;
    }

    public function get_order_client_payment($id) {
        $query = "select * from client_payments where order_id=$id";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        return $num;
    }

    public function get_supplier_payments_block($id, $status) {
        $list = "";
        if ($status == 1) {
            $list .= "<a href='#' onClick='return false;' style='color:black;' id='add_supplier_payment_$id'>Добавить оплату</a>";
        }  // end if $status==1
        else {
            $list .= "<table align='center' border='0'>";
            $query = "select * from supplier_order_payments where orderid=$id";
            $result = $this->db->query($query);
            foreach ($result->result() as $row) {
                $type = $this->get_payment_type_name($row->ptype);
                $date = date('d-m-Y  h:i:s', $row->pdate);
                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Данные поставщика</td><td style='padding:5px;'>$row->supplier_data</td>";
                $list .= "<tr>";

                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Оплаченная сумма</td><td style='padding:5px;'>$row->amount ($row->currency)</td>";
                $list .= "<tr>";

                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Эквивалент в долларах</td><td style='padding:5px;'>$$row->usd_amount</td>";
                $list .= "<tr>";

                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Тип оплаты</td><td style='padding:5px;'>$type</td>";
                $list .= "<tr>";

                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Коментарий</td><td style='padding:5px;'>$row->comment</td>";
                $list .= "<tr>";

                $list .= "<tr>";
                $list .= "<td style='padding:5px;'>Дата оплаты</td><td style='padding:5px;'>$date</td>";
                $list .= "<tr>";
            } // end foreach
            $list .= "</table>";
        } // end else

        return $list;
    }

    public function get_order_details3($id, $status) {
        $list = "";
        $query = "select * from orders where id=$id";
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

        $payments = $this->get_supplier_payments_block($id, $status);

        $list .= "<br><br><p align='center' style='font-weight:bold;'>Детали заказа</p>";

        $list .= "<br><table border='1' align='center' >";

        $list .= "<input type='hidden' value='$id' id='order_id'>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Название игры</td><td style='padding: 15px;'>$game->name</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Ник заказчика</td><td style='padding: 15px;'>$nick</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Email заказчика</td><td style='padding: 15px;'>$email</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Телефон заказчика</td><td style='padding: 15px;'>$phone</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>ICQ заказчика</td><td style='padding: 15px;'>$icq</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Skype заказчика</td><td style='padding: 15px;'>$skype</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Кол-во игровой валюты</td><td style='padding: 15px;'>" . $game_amount . "&nbsp;&nbsp;" . $game->currency . "</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Сумма к оплате</td><td style='padding: 15px;'>" . $amount . " " . $currency . "</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Сумма в долларах</td><td style='padding: 15px;'>" . $usd_amount . "USD</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Способ доставки</td><td style='padding: 15px;'>$method</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Оплаты поставщику</td><td style='padding: 15px;'><span id='supplier_payments'>$payments</span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Коментарий</td><td style='padding: 15px;'>" . $comment . "</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Дата заказа</td><td style='padding: 15px;'>" . $added . "</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding: 15px;'>Приметки менеджера:</td><td style='padding: 15px;'><textarea rows='4' id='notes' cols='35' disabled>$notes</textarea><br><span id='notes_err'></span></td>";
        $list .= "</tr>";

        $list .= "</table>";
        $list .= "<br/><hr/><br>";

        return $list;
    }

    public function search($status, $start, $end) {
        $list = "";
        $unix_start = strtotime($start);
        $unix_end = strtotime($end);
        $supplier_payments = array();

        $query = "select * from supplier_payments";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            // We have payment to suppliers
            foreach ($result->result() as $row) {
                $supplier_payments [] = $row->order_id;
            } // end foreach
            $supplier_payments_list = implode(',', $supplier_payments);
        } // end if $num > 0

        if ($status == 1) {
            $query = "select * from orders " . "where status=2 " . "and added between $unix_start " . "and $unix_end and id not in ($supplier_payments_list) " . "order by added desc";
            $result = $this->db->query($query);
            $num = $result->num_rows();
            if ($num > 0) {
                foreach ($result->result() as $row) {
                    $id = $row->id;
                    $list .= $this->get_order_details3($id, $status);
                } // end foreach
            }  // end if $num > 0
            else {
                $list .= "<p align='center'>Ничего не найдено</p>";
            }
        } // end if $status==1

        if ($status == 2) {
            $query = "select * from orders " . "where status=2 " . "and added between $unix_start " . "and $unix_end and id in ($supplier_payments_list) " . "order by added desc";
            // echo "Query: " . $query . "<br>";
            $result = $this->db->query($query);
            $num = $result->num_rows();
            if ($num > 0) {
                // We have payment to suppliers within selected dates
                foreach ($result->result() as $row) {
                    $list .= $this->get_order_details3($row->id, $status);
                } // end foreach
            }  // end if num>0
            else {
                $list .= "<p align='center'>Ничего не найдено</p>";
            } // end else
        } // end if $status==2

        return $list;
    }

    public function get_users_by_type($type) {
        $list = "";
        $list .= "<select id='users' style='width:95px;'>";
        $list .= "<option value='0' selected>Пользователи</option>";
        $query = "select * from users where type=$type order by firstname ";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $list .= "<option value='$row->id' >$row->firstname $row->lastname</option>";
            } // end foreach
        } // end if $num > 0
        $list .= "</select>";
        return $list;
    }

    public function get_user_accounts($type) {
        $list = "";
        $users = $this->get_users_by_type($type);
        $list .= "<div class=''>";
        $list .= "<form class='calc_form'>";
        $list .= "<br>";
        $list .= "<table align='center' border='0' style='width: 100%;'>";
        $list .= "<tr>";
        $list .= "<td align='center'>$users</td><td align='left'><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span></td>";
        $list .= "</tr>";
        $list .= "</table><br>";
        $list .= "</form>";
        $list .= "</div>";
        return $list;
    }

    public function get_rates() {
        $list = "";
        $query = "select * from exchange_rate";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $usd_rate = $row->usd_rate;
            $eur_rate = $row->eur_rate;
            $rur_rate = $row->rur_rate;
        }
        $list .= "<table align='center' border='0' style='width: 100%;'>";

        $list .= "<tr>";
        $list .= "<td style='padding:15px;'>Курс продажи USD*</td><td style='padding:15px;'><input type='text' id='usd' name='usd' value='$usd_rate'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding:15px;'>Курс продажи EUR*</td><td style='padding:15px;'><input type='text' id='eur' name='eur' value='$eur_rate'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding:15px;'>Курс продажи RUR*</td><td style='padding:15px;'><input type='text' id='rur' name='rur' value='$rur_rate'></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding:15px;' align='center' colspan='2'><span id='rate_err'></span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding:15px;' align='center' colspan='2'><button id=''>OK</button></td>";
        $list .= "</tr>";

        $list .= "</table>";

        return $list;
    }

    public function get_get_exchange_rate_page() {
        $list = "";
        $rates = $this->get_rates();
        $list .= "<div class=''>";
        $list .= "<form class='calc_form'  action='" . $this->config->item('base_url') . "index.php/user/update_rates' id='update_rates' method='post' >";
        $list .= "<br>";
        $list .= "<table align='center' border='0' style='width: 100%;'>";

        $list .= "<tr>";
        $list .= "<td align='center'><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center'><br><span id='container'>$rates</span></td>";
        $list .= "</tr>";

        $list .= "</table><br>";
        $list .= "</form>";
        $list .= "</div>";
        return $list;
    }

    public function update_rates($usd, $eur, $rur) {
        $clean_usd = str_replace(',', '.', $usd);
        $clean_eur = str_replace(',', '.', $eur);
        $clean_rur = str_replace(',', '.', $rur);
        $list = "";
        $query = "update exchange_rate " . "set usd_rate='$clean_usd', " . "eur_rate='$clean_eur', " . "rur_rate='$clean_rur' ";
        $this->db->query($query);
        $list .= "<br/><div class=''>";
        $list .= "<form class='calc_form'>";
        $list .= "<br>";
        $list .= "<table align='center' border='0' style='width: 100%;'>";
        $list .= "<tr>";
        $list .= "<td align='center'>Курсы валют успешно обновлены</td><td align='left'><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span></td>";
        $list .= "</tr>";
        $list .= "</table><br>";
        $list .= "</form>";
        $list .= "</div>";
        return $list;
    }

    public function get_orders_dropdown() {
        $list = "";
        $list .= "<select id='orders' name='orders' style='width:75px;'>";
        $list .= "<option value='1'>Необработанные</option>";
        $list .= "<option value='2'>В обработке </option>";
        $list .= "<option value='3'>Получены деньги от клиента</option>";
        $list .= "<option value='4'>Отданы клиенту</option>";
        $list .= "<option value='5'>Оплачены поставщику</option>";
        $list .= "</select>";
        return $list;
    }

    public function get_managers_list() {
        $list = "";
        $list .= "<select id='managers' name='managers' style='width:75px;'>";
        $list .= "<option value='0' selected>Менеджеры</option>";
        $query = "select * from users where type=2";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $list .= "<option value='$row->id'>$row->firstname $row->lastname</option>";
            } // end foreach
        } // end if $num > 0
        $list .= "</select>";
        return $list;
    }

    public function get_report_toolbar() {
        $list = "";
        $orders = $this->get_orders_dropdown();
        $managers = $this->get_managers_list();
        $list .= "<br><div><table align='center' border='0' style='width: 100%;'>";
        $list .= "<tr>";
        $list .= "<td style='padding:5px;'>$orders</td>";
        $list .= "<td style='padding:5px;'>Дата:*</td>";
        $list .= "<td style='padding:5px;'><input type='text' id='start' style='width:55px;'></td>";
        $list .= "<td style='padding:5px;'>Дата:*</td>";
        $list .= "<td style='padding:5px;'><input type='text' id='end' style='width:55px;'></td>";
        $list .= "<td style='padding:5px;'>$managers </td>";
        $list .= "<td style='padding:5px;'><a href='#' onClick='return false;' id='search_orders' style='color:black;'>OK</a></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center' colspan='7'><hr/></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center' colspan='7'><span id='orders_err'></span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center' colspan='7'><span id='ajax_loader' style='display:none;'><img src='/games/assets/images/ajax.gif' width='32' height='32' /></span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td colspan='7' align='center' style='padding:15px;'><span id='orders_container'></span></td>";
        $list .= "</tr>";

        $list .= "</table></div>";

        return $list;
    }

    public function get_orders_page() {
        $list = "";
        $toolbar = $this->get_report_toolbar();
        $list .= "<br/><div class=''>";
        $list .= "<form class='calc_form'>";
        $list .= "<br>";
        $list .= "<table align='center' border='0' style='width: 100%;'>";

        $list .= "<tr>";
        $list .= "<td align='center'><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center'>$toolbar</td>";
        $list .= "<tr>";

        $list .= "<tr>";
        $list .= "<td align='center'><span id='orders_container'></span></td>";
        $list .= "<tr>";

        $list .= "</tr>";
        $list .= "</table><br>";
        $list .= "</form>";
        $list .= "</div>";

        return $list;
    }

    public function search_orders($orders, $managers, $start, $end) {
        $list = "";

        $orders_array = array();
        $paid = array();
        $unix_start = strtotime($start);
        $unix_end = strtotime($end);

        $query0 = "select * from supplier_payments";
        $result = $this->db->query($query0);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $paid [] = $row->order_id;
            } // end foreach
        } // end if $num > 0
        $paid_list = implode(",", $paid);

        if ($managers == 0) {
            switch ($orders) {
                case 'non-processed' :
                    $query = "select * from orders where status=1 " . "and added between $unix_start and $unix_end " . "order by added desc ";
                    break;
                case 'processed' :
                    $query = "select * from orders where status=2 " . "and added between $unix_start and $unix_end " . "order by added desc ";
                    break;
                case 'non-paid' :
                    $query = "select * from orders " . "where status=2 " . "and id not in ($paid_list) " . "and added between $unix_start and $unix_end  " . "order by added desc";
                    break;
                case 'paid' :
                    $query = "select * from orders " . "where status=2 " . "and id in ($paid_list) " . "and added between $unix_start and $unix_end  " . "order by added desc";
                    break;
            } // end switch
        }  // end if $managers==0
        else {
            // Select orders by managers
            switch ($orders) {
                case 'non-processed' :
                    $query = "select * from orders where status=1 " . "and userid=$managers " . "and added between $unix_start and $unix_end " . "order by added desc ";
                    break;
                case 'processed' :
                    $query = "select * from orders where status=2 " . "and userid=$managers " . "and added between $unix_start and $unix_end " . "order by added desc ";
                    break;
                case 'non-paid' :
                    $query = "select * from orders " . "where status=2 " . "and userid=$managers " . "and id not in ($paid_list) " . "and added between $unix_start and $unix_end  " . "order by added desc";
                    break;
                case 'paid' :
                    $query = "select * from orders " . "where status=2 " . "and userid=$managers " . "and id in ($paid_list) " . "and added between $unix_start and $unix_end  " . "order by added desc";
                    break;
            } // end switch
        } // end else when we need report by managers
        $i = 0;
        $result2 = $this->db->query($query);
        $num2 = $result2->num_rows();
        if ($num2 > 0) {
            $list .= "<table>";

            $list .= "";
            $list .= "";

            foreach ($result2->result() as $row) {
                $order_detailes = $this->get_order_details($row->id, $row->status, false);
                $preface = $this->get_order_details2($row->id);
                $game_arr = $this->get_game_detailes($preface->gameid);
                $game = $game_arr ['game'];
                $gamename = $game->name;
                $date = date('Y-m-d h:i:s', $preface->added);

                $order_block = "";
                $order_block .= "<table width='100%'>";
                $order_block .= "<tr>";
                $order_block .= "<td width='50%' style='padding:15px;' width='75px;'>$gamename</td>";
                $order_block .= "<td width='25%' style='padding:15px;'>$date</td>";
                $order_block .= "<td  style='padding:15px;'><a href='#' onClick='return false;' style='color:black' id='get_details_$row->id'>Детали</a></td>";
                $order_block .= "</tr>";

                $order_block .= "<tr>";
                $order_block .= "<td colspan='3' align='center;'><span style='display:none;' id='det_$row->id'>$order_detailes</span></td>";
                $order_block .= "</tr>";

                $order_block .= "</table>";

                $list .= "<tr>";
                $list .= "<td style='padding:15px;'>$order_block</td>";
                $list .= "</tr>";
                $i ++;
            } // end foreach

            $list .= "<tr>";
            $list .= "<td style='padding:15px;'><span style='font-weight:bold;'>Всего заказов $i</span></td>";
            $list .= "</tr>";
            $list .= "</table>";
        }  // end if $num > 0
        else {
            $list .= "<p align='center'>Ничего не найдено</p>";
        }
        return $list;
    }

    public function get_revenue_toolbar() {
        $list = "";

        $games = $this->get_revenue_games();
        $list .= "<br><div><table align='center' border='0' style='width: 100%;'>";
        $list .= "<tr>";
        $list .= "<td style='padding:5px;'>$games</td>";
        $list .= "<td style='padding:5px;'>Дата:*</td>";
        $list .= "<td style='padding:5px;'><input type='text' id='start' style='width:55px;'></td>";
        $list .= "<td style='padding:5px;'>Дата:*</td>";
        $list .= "<td style='padding:5px;'><input type='text' id='end' style='width:55px;'></td>";
        $list .= "<td style='padding:5px;'><a href='#' onClick='return false;' id='get_revenue' style='color:black;'>OK</a></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center' colspan='7'><hr/></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center' colspan='7'><span id='revenue_err'></span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center' colspan='7'><span id='ajax_loader' style='display:none;'><img src='/games/assets/images/ajax.gif' width='32' height='32' /></span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td colspan='7' align='center' style='padding:15px;'><span id='chartdiv'></span></td>";
        $list .= "</tr>";

        $list .= "</table></div>";

        return $list;
    }

    public function get_currency_data() {
        $list = "";
        $query = "select * from exchange_rate";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $usd_rate = $row->usd_rate;
            $eur_rate = $row->eur_rate;
            $rur_rate = $row->rur_rate;
        }
        $list .= "<table align='center' border='1'>";

        $list .= "<tr>";
        $list .= "<td style='padding:15px;'>EUR</td><td style='padding:15px;'>USD</td><td style='padding:15px;'>RUR</td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td style='padding:15px;'>$eur_rate</td><td style='padding:15px;'>$usd_rate</td><td style='padding:15px;'>$rur_rate</td>";
        $list .= "</tr>";

        $list .= "</table>";

        return $list;
    }

    public function get_game_server_prices($id) {
        $list = "";
        $servers = array();
        $query = "select * from gameservers where gasGameID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $server = new stdClass ();
            $server->name = $row->gasName;
            $server->amount = $row->gasAmount;
            $server->price = $row->gasKurs;
            $servers [] = $server;
        }

        // echo "<pre>";
        // print_r($servers);
        // echo "</pre><br>";

        if (count($servers) > 0) {
            $list .= "<table style='table-layout: fixed, width:100%'>";
            foreach ($servers as $server) {
                $list .= "<tr>";
                $list .= "<td style='padding:7px;' width='70%'>$server->name</td><td style='padding:7px;'>$server->amount</td><td style='padding:7px;'>$$server->price</td>";
                $list .= "</tr>";
            } // end foreach
            $list .= "</table>";
        } // end if $servers) > 0
        return $list;
    }

    public function get_game_prices2() {
        $list = "";

        $query = "select * from games order by gamName";
        $result = $this->db->query($query);
        $list .= "<table align='center' border='1'>";
        $list .= "<tr>";
        $list .= "<th style='padding:15px;'>Игра</th><th style='padding:15px;'>Валюта</th><th style='padding:15px;'>Мин цена</th><th style='padding:15px;'>Макс цена</th>";
        $list .= "</tr>";
        foreach ($result->result() as $row) {
            $list .= "<tr>";
            $name = $row->gamName;
            $currency = $row->gamMoneys;
            $min_price = $row->min_price;
            $max_price = $row->max_price;
            $server_prices = $this->get_game_server_prices($row->gamID);
            $list .= "<td style='padding:15px;'>$name</td>";
            $list .= "<td style='padding:15px;'>$currency</td>";
            // $list.="<td style='padding:15px;' align='center'>$server_prices</td>";
            $list .= "<td style='padding:15px;' >$$min_price</td>";
            $list .= "<td style='padding:15px;'>$$max_price</td>";
            $list . "</tr>";
        }
        $list .= "</table>";
        return $list;
    }

    public function get_revenue_page() {
        $list = "";
        $toolbar = $this->get_revenue_toolbar();
        $exchange_rate = $this->get_currency_data();
        $prices = $this->get_game_prices2();

        $list .= "<br/><div class=''>";
        $list .= "<form class='calc_form'>";
        $list .= "<br>";
        $list .= "<table align='center' border='0' style='width: 100%;'>";

        $list .= "<tr>";
        $list .= "<td align='center'><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span></td>";
        $list .= "</tr>";

        $list .= "<tr>";
        $list .= "<td align='center'>$toolbar</td>";
        $list .= "<tr>";

        // $list.="<tr>";
        // $list.= "<td align='center'><br>$exchange_rate</td>";
        // $list.="</tr>";
        // $list.="<tr>";
        // $list.= "<td align='center'><br>$prices</td>";
        // $list.="<tr>";

        $list .= "<tr>";
        $list .= "<td align='center'><span id='orders_container'></span></td>";
        $list .= "<tr>";

        $list .= "</tr>";
        $list .= "</table><br>";
        $list .= "</form>";
        $list .= "</div>";

        return $list;
    }

    public function get_manager_data($id) {
        $query = "select * from users where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $user = $row->firstname . " " . $row->lastname;
        }
        return $user;
    }

    public function get_revenue($gameid, $start, $end) {
        $orders = array();
        $list = "";
        $u_start = strtotime($start);
        $u_end = strtotime($end) + 86400;
        $exchange_rate = $this->get_currency_data();
        $total = 0;

        if ($gameid > 0) {
            $query = "select 
			userid, 
			gameid, 
			serverid, 
			usd_amount, 
			currency_price, 
			game_amount, 
			status, 
			added 
			from orders 
			where gameid=$gameid 
			and status>1 
			and currency_price is not null 
			and added between $u_start and $u_end";
        } else {
            $query = "select 
			userid, 
			gameid, 
			serverid, 
			usd_amount, 
			currency_price, 
			game_amount, 
			status, 
			added from orders 
			where currency_price is not null 
			and status>1 
			and added between $u_start and $u_end ";
        }
        // echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $orders [] = $row;
            } // end foreach

            $this->create_csv_file($orders);

            $list .= "<table>";

            $list .= "<tr>";
            $list .= "<th style='padding:8px;' colspan='6' align='center'>$exchange_rate</th>";
            $list .= "</tr>";

            $list .= "<tr>";
            $list .= "<th style='padding:8px;' colspan='6' align='center'>Прибыль расчитывается с учетом клиентской скидки</th>";
            $list .= "</tr>";

            $list .= "</table>";

            $list .= "<table border='0' id='myTable' class='tablesorter'>";

            $list .= "<thead>";
            $list .= "<tr>";
            $list .= "<th style='padding:8px;'>Игра</th><th style='padding:8px;'>Менеджер</th><th style='padding:8px;'>Цена на сайте</th><th style='padding:8px;'>Кол-во</th><th style='padding:8px;'>Цена покупки</th><th style='padding:8px;'>Прибыль</th>";
            $list .= "</tr>";
            $list .= "</thead>";
            $list .= "<tbody>";
            foreach ($orders as $order) {
                $name = $this->get_game_name($order->gameid);
                $price = $this->get_game_server_price($order->serverid, $order->gameid);
                $user = $this->get_manager_data($order->userid);
                $server_unity = $this->get_server_currency_unity($order->serverid);
                $revenue = $this->get_single_game_revenue($order, $order->game_amount, $order->currency_price);
                $total = $total + $revenue;
                $list .= "<tr>";
                $list .= "<td style='padding:8px;'>$name</td><td style='padding:8px;'>$user</td><td style='padding:8px;'>$$price</td><td style='padding:8px;'>$order->game_amount" . $server_unity['amount'] . "</td><td style='padding:8px;'>$$order->currency_price</td><td style='padding:8px;'>$$revenue</td>";
                $list .= "</tr>";
            } // end foreach
            $list .= "</tbody>";
            $list .= "</table>";

            $list .= "<table border='0' align='right' style='margin-right:33px;'>";
            $list .= "<tr>";
            $list .= "<th style='padding:15px;'><a href='" . $this->config->item('base_url') . "tmp/report.csv' target='_blank'>Export to CSV</a></td><th style='padding:8px;'>$$total</th>";
            $list .= "</tr>";
            $list .= "</table>";
        }  // end if $num > 0
        else {
            $list .= "<p align='center'>Нет данных</p>";
        } // end else
        return $list;
    }

    function create_csv_file($orders) {
        $path = $this->path . '/report.csv';

        $output = fopen($path, 'w');
        fputcsv($output, array(
            'Игра',
            'Менеджер',
            'Цена на сайте',
            'Кол-во',
            'Цена покупки',
            'Прибыль'
        ));
        foreach ($orders as $order) {
            $name = $this->get_game_name($order->gameid);
            $price = $this->get_game_server_price($order->serverid, $order->gameid);
            $user = $this->get_manager_data($order->userid);
            $revenue = $this->get_single_game_revenue($order, $order->game_amount, $order->currency_price);
            fputcsv($output, array(
                $name,
                $user,
                $price,
                $order->game_amount,
                $order->currency_price,
                $revenue
            ));
        }
        fclose($output);
    }

    function get_exchange_rate($currency) {
        $query = "select * from exchange_rate";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $db_rate = $row;
        }
        switch ($currency) {
            case 'usd' :
                $rate = $db_rate->usd_rate;
                break;
            case 'rur' :
                $rate = $db_rate->rur_rate;
                break;
            case 'eur' :
                $rate = $db_rate->eur_rate;
                break;
        }
        return $rate;
    }

    function get_dicount_size($usd_amount) {
        // echo "USD amount: " . $usd_amount . "<br>";
        $rur_rate = $this->get_exchange_rate('rur');
        $usd_rate = $this->get_exchange_rate('usd');
        $rur_amount = $usd_amount / ($rur_rate / $usd_rate);
        // echo "RUR amount: " . $rur_amount . "<br>";
        if ($rur_amount >= 80000) {
            $discount = 5;
        }

        if ($rur_amount > 15000 && $rur_amount < 79999) {
            $discount = 4;
        }

        if ($rur_amount > 10000 && $rur_amount < 14999) {
            $discount = 3;
        }

        if ($rur_amount > 3000 && $rur_amount < 9999) {
            $discount = 2;
        }

        if ($rur_amount > 1000 && $rur_amount < 2999) {
            $discount = 1;
        }

        if ($rur_amount < 1000) {
            $discount = 0;
        }
        return $discount;
    }

    function get_single_game_revenue($order, $game_amount, $supplier_price) {
        // $supplier_price is in USD
        $server_unity = $this->get_server_currency_unity($order->serverid);
        $real_game_amount = $game_amount / $server_unity['qty'];
        $supplier_amount = $supplier_price * $real_game_amount;
        $revenue = $order->usd_amount - $supplier_amount;

        $this->revenue_total = $this->revenue_total + $revenue;
        return $revenue;
    }

    function get_game_server_price($id, $gameid) {
        $query = "select * from gameservers where gasID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $price = $row->gasKurs;
            $amount = $row->gasAmount;
            $qty = $row->gasQuantity;
        }

        $query = "select * from games where gamID=$gameid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $money = $row->gamMoney;
        }
        return $price . "/" . $qty . $amount . " " . $money;
    }

    function get_server_proce2($id) {
        $query = "select * from gameservers where gasID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $price = $row->gasKurs;
        }
        return $price;
    }

    function get_server_currency_unity($id) {
        $query = "select * from gameservers where gasID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $amount = $row->gasAmount;
            $qty = $row->gasQuantity;
        }
        $data = array('amount' => $amount, 'qty' => $qty);
        return $data;
    }

    function get_game_name($id) {
        $query = "select * from games where gamID=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $name = $row->gamName;
        }
        return $name;
    }

    public function add_supplier_order_payment($amount, $orderid) {
        $pure_amount = str_replace(',', '.', $amount);
        $query = "update orders set currency_price='$pure_amount' where id=$orderid";
        $this->db->query($query);
        $list = $this->get_order_currency_price($orderid);
        return $list;
    }

    public function get_order_currency_price2($id) {
        $query = "select * from orders where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $price = $row->currency_price;
        }
        return $price;
    }

    public function check_discount($email) {
        $list = "";
        $query = "select * from users_discount where email='$email'";
        // echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $discount = $this->get_dicount_size($row->usd_amount);
            } // end foreach
        }  // end $num > 0
        else {
            $discount = 0;
        }

        $list .= "Ваша скидка: $discount%";
        return $list;
    }

}
