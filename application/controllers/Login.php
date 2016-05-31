<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
        $this->load->model('games_model');
        $this->load->model('login_model');
    }

    public function get_common_elements() {
        $top_menu = $this->menu_model->get_top_menu();
        $games_list = $this->games_model->get_games_left_list();
        $data = array('top_menu' => $top_menu, 'games_list' => $games_list);
        return $data;
    }

    public function index() {
        $common_data = $this->get_common_elements();
        $page = $this->login_model->get_login_page();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function forgot() {
        $common_data = $this->get_common_elements();
        $page = $this->login_model->get_forgot_password_page();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function signup() {
        $common_data = $this->get_common_elements();
        $page = $this->login_model->get_signup_page();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function signupdone() {
        $user = new stdClass();
        $user->firstname = $this->input->post('firstname');
        $user->lastname = $this->input->post('lastname');
        $user->email = $this->input->post('email');
        $user->pwd = $this->login_model->get_password();
        $user->phone = $this->input->post('phone');
        $user->addr = $this->input->post('addr');
        $user->icq = $this->input->post('icq');
        $user->skype = $this->input->post('skype');
        $page = $this->login_model->add_user($user);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function restore() {
        $email = $this->input->post('email');
        $page = $this->login_model->forgot($email);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function restorepwd() {
        $id = $this->uri->segment(3);
        $page = $this->login_model->get_restore_pwd_form($id);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function restoredone() {
        $pwd = $this->input->post('pwd1');
        $userid = $this->input->post('userid');
        $page = $this->login_model->update_user_pwd($userid, $pwd);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

}
