<div id="container">
    <?php
    $this->load->view('top_menu_view');
    $this->load->view('left_block_view');
    $this->load->view('center_block_view');
    $url = $_SERVER['REQUEST_URI'];
    if (strpos($url, 'news') === FALSE && array_key_exists('email', $_SESSION) == FALSE) {
        $this->load->view('right_block_view');
    }
    if (!array_key_exists('email', $_SESSION) == FALSE && strpos($url, 'index.php') === FALSE) {
        $this->load->view('right_block_view');
    }
    ?>        
</div>
