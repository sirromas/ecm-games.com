<?php

class Server_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function edit() {
        $status = $this->user_model->validate_user();
        if ($status) {
            $type = $this->session->userdata('type');
            if ($type == 3) {
                
            } // end if $type == 3
        }  // end if $status
        else {
            redirect(base_url());
        }
    }
    
    

}
