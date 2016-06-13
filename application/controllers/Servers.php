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
    
    public function edit () {
        $status = $this->user_model->validate_user();
        
    }
    
    
}
