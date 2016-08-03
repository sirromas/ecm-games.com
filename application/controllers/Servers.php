<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Servers extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
        $this->load->model('games_model');
        $this->load->model('login_model');
        $this->load->model('user_model');
        $this->load->model('server_model');
    }

    public function update_server() {
        $server = new stdClass();
        $server->id = $this->input->post('id');
        $server->name = $this->input->post('name');
        $server->rate = $this->input->post('rate');
        $server->amount=$this->input->post('server_amount');
        $this->server_model->update($server);
    }

    public function get_common_elements() {
        $top_menu = $this->menu_model->get_top_menu();
        $games_list = $this->games_model->get_games_left_list();
        $data = array('top_menu' => $top_menu, 'games_list' => $games_list);
        return $data;
    }

    public function add_server() {
        $page = $this->server_model->add_server();
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function add_server_done() {      
        $server = new stdClass();
        $server->name = $this->input->post('name');
        $server->rate = $this->input->post('rate');
        $server->game = $this->input->post('game');        
        $page = $this->server_model->add_server_done($server);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

}
