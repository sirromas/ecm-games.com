<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {

    function index() {
        $this->load->view('page_view');
    }

}
