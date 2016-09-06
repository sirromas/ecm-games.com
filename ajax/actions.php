<?php

/**
 * Description of actions
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/ajax/class.pdo.database.php';

class Actions {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function is_email_exists($email) {
        $query = "select * from users where email='$email'";        
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_valid_user($email, $pwd) {
        $query = "select * from users where email='$email' and pwd='$pwd'";
        $num = $this->db->numrows($query);
        return $num;
    }

}
