<?php

class Games extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('games_model');
    }
    
    public function game_block () {
        $gameID=$this->uri->segment(3);
        $gameBlock=$this->games_model->game_block($gameID);
        
    }

}
