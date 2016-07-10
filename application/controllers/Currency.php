<?php

class Currency extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('games_model');
    }

    public function rate() {
        $currency = $this->games_model->get_rate();
        echo $currency;
    }

}
