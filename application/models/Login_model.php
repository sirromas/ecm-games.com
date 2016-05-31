<?php

class login_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->config->load('email');
        $this->load->library('email');
    }

    public function get_login_page() {
        $list = "";
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' >";
        $list.= "<br><br><br><br><br><br>";
        $list.= "<table align='center'><tr><td>";
        $list.="<span class='2'>Логин:</span></td>";
        $list.="<td><span class='2'><input type='text' id='username' name='username'></span></td>";
        $list.="</tr>";
        $list.= "<tr>";
        $list.="<td><span class='2'>Пароль:</span></td>";
        $list.="<td><span class='2'><input type='password' id='pwd' name='pwd'></span></td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td></td><td><span class='2'><button class='calc_order_send' type='submit'>OK</button></span></td></tr>";
        $list.="<tr><td></td><td><a href='http://" . $_SERVER['SERVER_NAME'] . "/games/index.php/login/forgot' style='color: #000000;font-size: 14px;text-decoration: none;'>Забыл пароль</a></span></td></tr>";
        $list.="<tr><td></td><td><a href='http://" . $_SERVER['SERVER_NAME'] . "/games/index.php/login/signup' style='color: #000000;font-size: 14px;text-decoration: none;'>Зарегистрироваться</a></td></tr>";
        $list.="</table><br><br><br><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function get_forgot_password_page() {
        $list = "";
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' >";
        $list.= "<br><br>";
        $list.= "<table align='center' border='0'>";
        $list.="<tr><td colspan='2' align='center'>Пожалуйста укажите Email</td></tr>";
        $list.= "<tr>";
        $list.="<td><span class='2'>Ваш Email:</span></td>";
        $list.="<td><span class='2'><input type='text' id='email' name='email'></span></td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td></td><td><span class='2'><button class='calc_order_send' type='submit'>OK</button></span></td></tr>";

        $list.="</table><br><br>";
        $list.="<div style='text-align:center;' id='forgot_result'></div>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function forgot_process() {
        
    }

    function get_password($length = 8) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public function get_signup_page() {
        $list = "";
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' id='signup_form' action='http://" . $_SERVER['SERVER_NAME'] . "/games/index.php/login/signupdone' method='post'>";
        $list.= "<br><br>";
        $list.= "<table align='center' border='0'>";

        $list.= "<tr>";
        $list.="<td><span class='2'>Имя*:</span></td>";
        $list.="<td><span class='2'><input type='text' id='firstname' name='firstname'></span></td>";
        $list.="</tr>";

        $list.= "<tr>";
        $list.="<td><span class='2'>Фамилия*:</span></td>";
        $list.="<td><span class='2'><input type='text' id='lastname' name='lastname'></span></td>";
        $list.="</tr>";

        $list.= "<tr>";
        $list.="<td><span class='2'>Ваш Email*:</span></td>";
        $list.="<td><span class='2'><input type='text' id='email' name='email'></span></td>";
        $list.="</tr>";

        $list.= "<tr>";
        $list.="<td><span class='2'>Телефон*:</span></td>";
        $list.="<td><span class='2'><input type='text' id='phone' name='phone'></span></td>";
        $list.="</tr>";

        $list.= "<tr>";
        $list.="<td><span class='2'>Адрес*:</span></td>";
        $list.="<td><span class='2'><input type='text' id='addr' name='addr'></span></td>";
        $list.="</tr>";

        $list.= "<tr>";
        $list.="<td><span class='2'>ICQ:</span></td>";
        $list.="<td><span class='2'><input type='text' id='icq' name='icq'></span></td>";
        $list.="</tr>";

        $list.= "<tr>";
        $list.="<td><span class='2'>Skype:</span></td>";
        $list.="<td><span class='2'><input type='text' id='skype' name='skype'></span></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td></td><td><span class='2'><button class='calc_order_send' type='button' id='signup'>OK</button></span></td></tr>";

        $list.="</table><br><br>";
        $list.="<div style='text-align:center;' id='signup_err'></div>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function send_signup_confirmation_email($user) {
        $user->email = 'sirromas@gmail.com'; // temp workaround
        $msg = "<html>";
        $msg.="<body>";
        $msg.="<p align='center'>Уважаемый(я) $user->firstname $user->lastname!</p>";
        $msg.="<p align='center'>Спасибо за регистрацию!</p>";
        $msg.="<p align='center'>Ваш логин: $user->email</p>";
        $msg.="<p align='center'>Ваш пароль: $user->pwd</p>";
        $msg.="<p align='center'>Если Вам нужна помощь свяжитесь с нами по email " . $this->config->item('smtp_user') . "</p>";
        $msg.="<p align='center'>С уважением,<br> Администрация сайта.</p>";
        $msg.="</body>";
        $msg.="</html>";

        $this->email->from($this->config->item('smtp_user'), 'ECM-GAMES');
        $this->email->to($user->email);
        $this->email->subject('ECM-GAMES Подтверждение регистрации');
        $this->email->message($msg);
        $this->email->send();
    }

    public function add_user($user) {
        $list = "";
        $query = "select * from users where email='$user->email'";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num == 0) {
            $query = "insert into users "
                    . "(firstname,"
                    . "lastname,"
                    . "email,"
                    . "pwd,"
                    . "phone,"
                    . "addr,"
                    . "skype,"
                    . "icq) "
                    . " values (" . $this->db->escape($user->firstname) . ", "
                    . "" . $this->db->escape($user->lastname) . ","
                    . "" . $this->db->escape($user->email) . ","
                    . "" . $this->db->escape($user->pwd) . ","
                    . "" . $this->db->escape($user->phone) . ","
                    . "" . $this->db->escape($user->addr) . ","
                    . "" . $this->db->escape($user->skype) . ","
                    . "" . $this->db->escape($user->icq) . ")";
            $this->db->query($query);
        } // end if $num==0
        $this->send_signup_confirmation_email($user);
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' >";
        $list.= "<br>";
        $list.="<p align='center'>Спасибо за регистрацию. Мы отправили Вам письмо на $user->email.</p>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

}
