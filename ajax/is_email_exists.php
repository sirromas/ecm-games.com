<?php

require_once './actions.php';
$ac = new Actions();
$email = $_POST['email'];
$num = $ac->is_email_exists($email);
//$num=1;
echo $num;

