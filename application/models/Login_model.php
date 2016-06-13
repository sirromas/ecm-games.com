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
        $list.="<form class='calc_form' id='login_form' method='post' action='" . $this->config->item('base_url') . "index.php/user/auth'>";
        $list.= "<br><br><br><br><br><br>";
        $list.= "<table align='center'>";
        $list.="<tr><td colspan='2' align='center'><span id='login_err'></span></td></tr>";
        $list.="<tr><td><span class='2'>Логин:</span></td>";
        $list.="<td><span class='2'><input type='text' id='username' name='username'></span></td>";
        $list.="</tr>";
        $list.= "<tr>";
        $list.="<td><span class='2'>Пароль:</span></td>";
        $list.="<td><span class='2'><input type='password' id='pwd' name='pwd'></span></td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td></td><td><span class='2'><button class='calc_order_send' id='login' type='button'>OK</button></span></td></tr>";
        $list.="<tr><td></td><td><a href='" . $this->config->item('base_url') . "index.php/login/forgot' style='color: #000000;font-size: 14px;text-decoration: none;'>Забыл пароль</a></span></td></tr>";
        $list.="<tr><td></td><td><a href='" . $this->config->item('base_url') . "index.php/login/signup' style='color: #000000;font-size: 14px;text-decoration: none;'>Зарегистрироваться</a></td></tr>";
        $list.="</table><br><br><br><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function get_forgot_password_page() {
        $list = "";
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' method='post' id='restore_pwd' action='" . $this->config->item('base_url') . "index.php/login/restore/'>";
        $list.= "<br><br>";
        $list.= "<table align='center' border='0'>";
        $list.="<tr><td colspan='2' align='center'>Пожалуйста укажите Email</td></tr>";
        $list.= "<tr>";
        $list.="<td><span class='2'>Ваш Email:</span></td>";
        $list.="<td><span class='2'><input type='text' id='email' name='email'></span></td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td></td><td><span class='2'><button class='calc_order_send' type='button' id='restore_btn'>OK</button></span></td></tr>";
        $list.="<td></td><td><span class='2'><a href='".$this->config->item('base_url')."index.php/login/signup' target='_blank' style='color: #000000;font-size: 14px;text-decoration: none;'>Зарегистрироваться</a></span></td></tr>";
        $list.="</table><br><br>";
        $list.="<div style='text-align:center;' id='forgot_err'></div>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function get_restore_pwd_form($id) {
        $list = "";
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' method='post' id='restore_pwd' action='" . $this->config->item('base_url') . "index.php/login/restoredone'>";
        $list.= "<br><br>";
        $list.= "<input type='hidden' id='userid' name='userid' value='$id'>";
        $list.= "<table align='center' border='0'>";
        $list.="<tr><td colspan='2' align='center'>Пожалуйста введите новый пароль</td></tr>";

        $list.= "<tr>";
        $list.="<td><span class='2'>Новый пароль*:</span></td>";
        $list.="<td><span class='2'><input type='password' id='pwd1' name='pwd1'></span></td>";
        $list.="</tr>";

        $list.= "<tr>";
        $list.="<td><span class='2'>Новый пароль(повтор)*:</span></td>";
        $list.="<td><span class='2'><input type='password' id='pwd2' name='pwd'></span></td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td></td><td><span class='2'><button class='calc_order_send' type='button' id='restore_btn_done'>OK</button></span></td></tr>";
        $list.="</table><br><br>";
        $list.="<div style='text-align:center;' id='forgot_err'></div>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function send_restore_pwd_link($user) {
        $url = $this->config->item('base_url') . "index.php/login/restorepwd/$user->id";
        $msg = "<html>";
        $msg.="<body>";
        $msg.="<p align='center'>Уважаемый(я) $user->firstname $user->lastname!</p>";
        $msg.="<p align='center'>Вы запросили смену пароля для вашей учетной записи на сайте eсm-games.com.</p>";
        $msg.="<p align='center'>Если это были не Вы, проигнорируйте это письмо.</p>";
        $msg.="<p align='center'>Пожалуйста перейдите по этой <a href='$url' target='_blank'>ссылке</a> для смены пароля.</p>";
        $msg.="<p align='center'>Если Вам нужна помощь, свяжитесь с нами по email " . $this->config->item('smtp_user') . "</p>";
        $msg.="<p align='center'>С уважением,<br> Администрация сайта.</p>";
        $msg.="</body>";
        $msg.="</html>";
        $this->email->from($this->config->item('smtp_user'), 'ECM-GAMES');
        $this->email->to($user->email);
        $this->email->subject('ECM-GAMES Запрос на смену пароля');
        $this->email->message($msg);
        $this->email->send();
    }

    public function forgot($email) {
        $list = "";
        $query = "select * from users where email='$email'";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $user = new stdClass();
                $user->id = $row->id;
                $user->firstname = $row->firstname;
                $user->lastname = $row->lastname;
                $user->email = $row->email;
            }
            $this->send_restore_pwd_link($user);
        } // end if $num>0
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' >";
        $list.= "<br>";
        $list.="<p align='center'>Если Вы правильно указали Email, Вы должны получить письмо с инструкциями.</p>";
        $list.="<p align='center'>Спасибо за обращение, <br> С уважением, администрация сайта.</p>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    function get_password($length = 8) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public function get_signup_page() {
        $list = "";
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' id='signup_form' action='" . $this->config->item('base_url') . "index.php/login/signupdone' method='post'>";
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

        $list.="<tr><td></td><td><span class='2'><button class='calc_order_send' type='button' id='signup'>OK</button></span></td></tr>";
        $list.="<tr><td></td><td><span class='2'><a target='_blank' href='" . $this->config->item('base_url') . "index.php/login/forgot' style='color: #000000;font-size: 14px;text-decoration: none;'>Забыл пароль</a></span></td></tr>";
        
        $list.="</table><br><br>";
        $list.="<div style='text-align:center;' id='signup_err'></div>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

    public function send_signup_confirmation_email($user) {
        //$user->email = 'sirromas@gmail.com'; // temp workaround
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

    function send_pwd_confirmation($user) {
        //$user->email = 'sirromas@gmail.com'; // temp workaround
        $msg = "<html>";
        $msg.="<body>";
        $msg.="<p align='center'>Уважаемый(я) $user->firstname $user->lastname!</p>";
        $msg.="<p align='center'>Ваш пароль успешно обновлен.</p>";
        $msg.="<p align='center'>Ваш логин: $user->email</p>";
        $msg.="<p align='center'>Ваш пароль: $user->pwd</p>";
        $msg.="<p align='center'>Если Вам нужна помощь свяжитесь с нами по email " . $this->config->item('smtp_user') . "</p>";
        $msg.="<p align='center'>С уважением,<br> Администрация сайта.</p>";
        $msg.="</body>";
        $msg.="</html>";
        $this->email->from($this->config->item('smtp_user'), 'ECM-GAMES');
        $this->email->to($user->email);
        $this->email->subject('ECM-GAMES Смена пароля');
        $this->email->message($msg);
        $this->email->send();
    }

    public function update_user_pwd($userid, $pwd) {
        $list = "";
        $query = "select * from users where id=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $user = new stdClass();
            $user->id = $row->id;
            $user->pwd = $pwd;
            $user->firstname = $row->firstname;
            $user->lastname = $row->lastname;
            $user->email = $row->email;
        }
        $query2 = "update users set pwd='$pwd' where id=$userid";
        $this->db->query($query2);
        $this->send_pwd_confirmation($user);
        $list.="<br/><div class='calc'>";
        $list.="<form class='calc_form' >";
        $list.= "<br>";
        $list.="<p align='center'>Пароль успешно обновлен. Мы отправили Вам письмо на $user->email.</p>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

}
