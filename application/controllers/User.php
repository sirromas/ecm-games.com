<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
        $this->load->model('games_model');
        $this->load->model('login_model');
        $this->load->model('user_model');
    }

    public function get_common_elements() {
        $top_menu = $this->menu_model->get_top_menu();
        $games_list = $this->games_model->get_games_left_list();
        $data = array('top_menu' => $top_menu, 'games_list' => $games_list);
        return $data;
    }

    public function auth() {
        $email = $this->input->post('username');
        $pwd = $this->input->post('pwd');
        $type = $this->user_model->authorize($email, $pwd);
        if ($type > 0) {
            redirect("/user/page/$type");
        } // end if $type
        else {
            redirect("/menu/page/login");
        } // end else                      
    }

    public function page() {
        $type = $this->uri->segment(3);
        $page = $this->user_model->get_user_dashboard($type);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function logout() {
        $page = $this->user_model->get_exit_page();
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function logoutdone() {
        $this->user_model->logout();
    }

    public function add_user() {
        $page = $this->user_model->add_user();
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function edit() {
        $id = $this->uri->segment(3);
        $page = $this->user_model->get_edit_block($id);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function edit_done() {
        $user = new stdClass();
        $user->id = $this->input->post('id');
        $user->firstname = $this->input->post('firstname');
        $user->lastname = $this->input->post('lastname');
        $user->pwd = $this->input->post('pwd');
        $user->phone = $this->input->post('phone');
        $user->addr = $this->input->post('addr');
        $user->skype = $this->input->post('skype');
        $user->icq = $this->input->post('icq');
        $user->type = $this->input->post('type');
        $user->games = $this->input->post('manager_games');
        $page = $this->user_model->update_user($user);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function report() {
        $page = $this->user_model->report();
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function added_done() {
        /*
          print_r($_REQUEST);
          echo "<br>";
          die('Stopped ...');
         */

        $user = new stdClass();
        $user->firstname = $_REQUEST['firstname'];
        $user->lastname = $_REQUEST['lastname'];
        $user->email = $_REQUEST['email'];
        $user->pwd = $_REQUEST['pwd'];
        $user->phone = $_REQUEST['phone'];
        $user->addr = $_REQUEST['address'];
        $user->skype = $_REQUEST['skype'];
        $user->icq = $_REQUEST['icq'];
        $user->type = $_REQUEST['user_type'];
        if ($user->type == 2) {
            $user->games = $_REQUEST['manager_games'];
        } // end if $user->type == 2
        else {
            $user->games = '';
        }
        $page = $this->user_model->user_added($user);
        $common_data = $this->get_common_elements();
        $method_data = array('page' => $page);
        $data = array_merge($common_data, $method_data);
        $this->load->view('page_view', $data);
    }

    public function del_user() {
        $id = $_REQUEST['id'];
        $this->user_model->del_user($id);
    }

    public function add_order() {
        $order = $_REQUEST['order'];
        $list = $this->user_model->add_order($order);
        echo $list;
    }

    public function get_order_details() {
        $id = $_REQUEST['id'];
        $status = $_REQUEST['status'];
        $list = $this->user_model->get_order_details($id, $status);
        echo $list;
    }

    public function update_order_notes() {
        $id = $_REQUEST['id'];
        $notes = $_REQUEST['notes'];
        $list = $this->user_model->updta_order_notes($id, $notes);
        echo $list;
    }

    public function set_order_status() {
        $id = $_REQUEST['id'];
        $status = $_REQUEST['status'];
        $list = $this->user_model->set_order_status($id, $status);
        echo $list;
    }

    public function get_add_payment_modal_box() {
        $id = $_REQUEST['id'];
        $list = $this->user_model->get_add_payment_modal_box($id);
        echo $list;
    }

    public function get_add_payment_modal_box2() {
        $id = $_REQUEST['id'];
        $list = $this->user_model->get_add_payment_modal_box2($id);
        echo $list;
    }

    public function add_payment() {
        $id = $_REQUEST['id'];
        $amount = $_REQUEST['amount'];
        $ptype = $_REQUEST['ptype'];
        $comment = $_REQUEST['comment'];
        $list = $this->user_model->add_payment($id, $amount, $comment, $ptype);
        echo $list;
    }

    public function add_supplier_payment() {        
        $id = $_REQUEST['id'];
        $supplier_data=$_REQUEST['supplier_data'];
        $amount = $_REQUEST['amount'];
        $ptype = $_REQUEST['ptype'];
        $comment = $_REQUEST['comment'];
        $list = $this->user_model->add_supplier_payment($id, $amount, $comment, $ptype,$supplier_data);
        echo $list;
    }

    public function get_order_client_payments() {
        $id = $_REQUEST['id'];
        $list = $this->user_model->get_order_client_payment($id);
        echo $list;
    }

    public function get_cashier_orders() {
        $status = $_REQUEST['status'];
        $start = $_REQUEST['start'];
        $end = $_REQUEST['end'];
        $list = $this->user_model->search($status, $start, $end);
        echo $list;
    }

}
