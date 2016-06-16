<?php

class Menu_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }

    public function get_menu_russian_name($name) {
        switch ($name) {
            case 'index':
                $item = "Главная";
                break;
            case 'news':
                $item = 'Новости';
                break;
            case 'buy':
                $item = 'Как купить';
                break;
            case 'garant':
                $item = 'Услуги Гаранта';
                break;
            case 'supplier':
                $item = 'Поставщикам';
                break;
            case 'guarantee':
                $item = 'Гарантии';
                break;
            case 'contact':
                $item = 'Контакты';
                break;
            case 'about':
                $item = 'О нас';
                break;
            case 'login':
                $status = $this->session->userdata('email');
                if ($status == '') {
                    $item = 'Вход';
                } // end if $status==''
                else {
                    $item = 'Выход';
                } // end else 
                break;
        }
        return $item;
    }

    public function get_top_menu() {
        $list = "";
        $query = "select * from top_menu";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $menu_name = $this->get_menu_russian_name($row->name);
            if ($row->name != 'index') {
                if ($row->name != 'login') {
                    $link = $this->config->item('base_url') . "index.php/menu/page/" . $row->link . "";
                } // end if $row->name!=login
                else {
                    $status = $this->session->userdata('email');
                    if ($status == '') {
                        $link = $this->config->item('base_url') . "index.php/menu/page/" . $row->link . "";
                    } // end if $stattus == ''
                    else {
                        $link = $this->config->item('base_url') . "index.php/user/logoutdone/";
                    } // end else 
                } // end else
            } // end if $row->name!='index'
            else {
                $link = $this->config->item('base_url');
            } // end else     
            $list.="<li style='min-width: 11.1%;'><a href='$link' title='$menu_name'>$menu_name</a></li>";
        } // end foreach
        return $list;
    }

    function get_page_content($item) {
        $list = "";
        $query = "select * from top_menu where link='$item'";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.=$row->content;
        }
        return $list;
    }

    function get_admin_page($id) {
        $list = "";
        switch ($id) {
            case 9719147:
                $title = "Новости";
                break;
            case 9719146:
                $title = "Как купить";
                break;
            case 9719145:
                $title = "Услуги гаранта";
                break;

            case 9719143:
                $title = "Поставщикам";
                break;

            case 9719144:
                $title = "Гарантии";
                break;

            case 3068:
                $title = "Контакты";
                break;

            case 1:
                $title = "О нас";
                break;
        }

        $query = "select * from content where cntID=$id and cntLanguageID=2";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $id = $row->cntID;
            $content = $row->cntBody;
        }
        $list.="<form id='user_page' method='post' action='" . $this->config->item('base_url') . "index.php/menu/update_page'>";
        $list.="<input type='hidden' id='id' name='id' value='$id'>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>$title</span>";
        $list.="<span class='2'><a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>&nbsp;&nbsp;Меню</a></span>";
        $list.="</div>";
        $list.="<br><div class='container-fluid' style='text-align:left;'>
                <input type='hidden' id='id' value='$id'>
                <textarea name='body' >$content</textarea>
                <script>
                CKEDITOR.replace('body');
                </script>
                </div>";
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span2'><button id='update_user_page' style='background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #fdfbec 0%, #e3c271 48%, #d9ad43 50%, #ce9428 100%) repeat scroll 0 0;
                                                                            border: 0 none;
                                                                            border-radius: 15px;
                                                                            box-shadow: 0 0 20px 1px #000000;
                                                                            color: #000000;
                                                                            font-size: 16px;
                                                                            font-weight: bold;
                                                                            line-height: 50px;
                                                                            margin: 10px auto;
                                                                            text-align: center;
                                                                            text-decoration: none;
                                                                            text-shadow: 1px 1px 1px #ffffff;
                                                                            text-transform: uppercase;
                                                                            width: 150px;'>Ok</button></span>";
        $list.="</div>";
        return $list;
    }

    function update_user_page($id, $body) {
        $list = "";
        $query = "update content "
                . "set cntBody='$body' "
                . "where cntID=$id "
                . "and cntLanguageID=2";
        $this->db->query($query);
        $list.="<br/><div class=''>";
        $list.="<form class='calc_form'>";
        $list.= "<br>";
        $list.= "<table align='center' border='0' style='width: 100%;'>";
        $list.="<tr>";
        $list.= "<td>&nbsp;&nbsp;<span>Страница успешно добавлена. &nbsp; <a href='" . $this->config->item('base_url') . "index.php/user/page/" . $this->session->userdata('type') . "' style='color: #000000;font-size: 14px;text-decoration: none;font-weight:bolder;'>Меню</a></span><td>";
        $list.= "</tr>";
        $list.= "</table><br>";
        $list.="</form>";
        $list.="</div>";
        return $list;
    }

}
