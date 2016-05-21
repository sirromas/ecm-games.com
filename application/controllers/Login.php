<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model');
    }

    public function index() {
        $page = $this->login_model->get_login_page();
        $data = array('page' => $page);
        $this->load->view('page_view', $data);
    }

}
