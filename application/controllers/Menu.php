<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
        $this->load->model('login_model');
        $this->load->model('games_model');
    }

    public function get_top_menu() {
        $top_menu = $this->menu_model->get_top_menu();
        return $top_menu;
    }

    public function get_common_elements() {
        $top_menu = $this->menu_model->get_top_menu();
        $games_list = $this->games_model->get_games_left_list();
        $data = array('top_menu' => $top_menu, 'games_list' => $games_list);
        return $data;
    }

    public function page() {
        $item = $this->uri->segment(3);
        $common_data = $this->get_common_elements();
        if ($item != 'login') {
            $page = $this->menu_model->get_page_content($item);
        } // end if $item!='login'
        else {
            $page = $this->login_model->get_login_page();
        }
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

}
