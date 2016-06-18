<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
        $this->load->model('games_model');
        $this->load->model('login_model');
        $this->load->model('user_model');
    }

    public function get_common_elements() {
        $top_menu = $this->menu_model->get_top_menu();
        $games_list = $this->games_model->get_games_left_list();
        $data = array('top_menu' => $top_menu, 'games_list' => $games_list);
        return $data;
    }

    public function auth() {
        $email = $this->input->post('username');
        $pwd = $this->input->post('pwd');
        $type = $this->user_model->authorize($email, $pwd);
        if ($type > 0) {
            redirect("/user/page/$type");
        } // end if $type
        else {
            redirect("/menu/page/login");
        } // end else                      
    }

    public function page() {
        $type = $this->uri->segment(3);
        $page = $this->user_model->get_user_dashboard($type);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function logout() {
        $page = $this->user_model->get_exit_page();
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function logoutdone() {
        $this->user_model->logout();
    }

    public function add_user() {
        
    }

    public function edit() {
        $id = $this->uri->segment(3);
        $page = $this->user_model->get_edit_block($id);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function edit_done() {
        $user = new stdClass();
        $user->id = $this->input->post('id');
        $user->firstname = $this->input->post('firstname');
        $user->lastname = $this->input->post('lastname');
        $user->pwd = $this->input->post('pwd');
        $user->phone = $this->input->post('phone');
        $user->addr = $this->input->post('addr');
        $user->skype = $this->input->post('skype');
        $user->icq = $this->input->post('icq');
        $user->type = $this->input->post('type');
        $page = $this->user_model->update_user($user);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }
    
    public function report () {
        $page = $this->user_model->report();
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

}
