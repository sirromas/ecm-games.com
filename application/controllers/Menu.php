<script src="http://mycodebusters.com/games/ckeditor/ckeditor.js"></script>

<?php

class Menu extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
        $this->load->model('login_model');
        $this->load->model('games_model');
        $this->load->library('session');
    }

    public function get_top_menu() {
        $top_menu = $this->menu_model->get_top_menu();
        return $top_menu;
    }

    public function get_common_elements() {
        $top_menu = $this->menu_model->get_top_menu();
        $games_list = $this->games_model->get_games_left_list();
        $data = array('top_menu' => $top_menu, 'games_list' => $games_list);
        return $data;
    }

    public function page() {
        $type = $this->session->userdata('type');
        $item = $this->uri->segment(3);
        $common_data = $this->get_common_elements();
        
        if ($type == '') {
            if ($item != 'login') {
                //echo "Inside w/o login if ...<br>";
            	$page = $this->menu_model->get_page_content($item);
            	//echo "Menu page: ".$page."<br>";
            } // end if $item!='login'
            else {
                //echo "Inside w/o login else<br>";
            	$page = $this->login_model->get_login_page();
            	//echo "Menu page: ".$page."<br>";
            } // end else        
        } // end if $type == ''
        
        else {
            if ($type == 3) {
                // Admin
            	if ($item=='discount') {
            		$page=$this->menu_model->get_disocunt_page();
            		//echo "Menu page: ".$page."<br>";
                }
                else {
                	$page = $this->menu_model->get_admin_page2($item);
                	//echo "Menu page: ".$page."<br>";
                }
            }  // end if $type == 3
            
            if ($type==1) {
            	// Partner
            	switch ($item) {
            		case "login":
            			$page = $this->login_model->get_login_page();
            			//echo "Menu page: ".$page."<br>";
            			break;
            		case "discount":
            			$page=$this->menu_model->get_disocunt_page();
            			//echo "Menu page: ".$page."<br>";
            			break;
            		default:
            			$page = $this->menu_model->get_page_content($item);
            			//echo "Menu page: ".$page."<br>";
            	} // end switch
             } // end if $type==1
        } // end else when user is authorized
        
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function adminpage() {
        $item = $this->uri->segment(3);
        $common_data = $this->get_common_elements();
        $page = $this->menu_model->get_admin_page($item);
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function update_page() {
        $body = $this->input->post('body');
        $id = $this->input->post('id');
        $common_data = $this->get_common_elements();
        $page = $this->menu_model->update_user_page($id, $body);
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function fullnews() {
        $id = $this->uri->segment(3);
        $common_data = $this->get_common_elements();
        $page = $this->menu_model->full_news($id);
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function search_news() {
        $start = $_REQUEST['start'];
        $end = $_REQUEST['end'];
        $list = $this->menu_model->get_news_by_date($start, $end);
        echo $list;
    }

    public function get_add_news_modal_box() {
        $list = $this->menu_model->get_add_news_modal_box();
        echo $list;
    }

    public function add_news() {
        $title = $_REQUEST['title'];
        $body = $_REQUEST['body'];
        $list = $this->menu_model->add_news($title, $body);
        echo $list;
    }

    public function edit_news() {
        $id = $_REQUEST['id'];
        $list = $this->menu_model->get_edit_news_box($id);
        echo $list;
    }

    public function update_news() {
        $id = $_REQUEST['id'];
        $title = $_REQUEST['title'];
        $body = $_REQUEST['body'];
        $list = $this->menu_model->update_news($id, $title, $body);
        echo $list;
    }

    public function del_news() {
        $id = $_REQUEST['id'];
        $list = $this->menu_model->del_news($id);
        echo $list;
    }

}
