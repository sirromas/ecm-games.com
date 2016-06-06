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

}
