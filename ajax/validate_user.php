<?php

require_once './actions.php';
$ac = new Actions();
$email = $_POST['email'];
$pwd = $_POST['pwd'];
$num = $ac->is_valid_user($email, $pwd);
echo $num;
