<?php
/**
 * Created by PhpStorm.
 * User: Andrii
 */

print_d("order model start");

    require_once('system/mailer/PHPMailerAutoload.php');
    $mail = new PHPMailer; //Create a new PHPMailer instance
    $mail->CharSet = 'utf-8';

    if (isset($_POST['game_id']) && $_POST['game_id'] > 0)
    {
        $orderInfo = "
<strong><%%>Game<%%>:</strong>".$_POST['game_name']."<br/>
<strong><%%>Server<%%>:</strong>".$_POST['inp_server']."<br/>
<strong><%%>Payment method<%%>:</strong>".$_POST['inp_payment']."<br/>
<strong><%%>Gold count<%%>:</strong>".$_POST['inp_zoloto']."<br/>
<strong><%%>Total<%%>:</strong>".$_POST['inp_money']."<br/>
<hr/><h3><%%>Contact info<%%>:</h3><br/>
".(isset($_POST['inp_phone']) && $_POST['inp_phone'] ? "<strong><%%>Phone<%%>:</strong>".$_POST['inp_phone']."<br/>" : "")."
".(isset($_POST['inp_skype']) && $_POST['inp_skype'] ? "<strong><%%>Skype<%%>:</strong>".$_POST['inp_skype']."<br/>" : "")."
".(isset($_POST['inp_icq']) && $_POST['inp_icq'] ? "<strong><%%>ICQ<%%>:</strong>".$_POST['inp_icq']."<br/>" : "")."
<hr/>
<strong><%%>Delivery<%%>:</strong>".$_POST['inp_delivery']."<br/>
<hr/>
<h3><%%>Additional info<%%>:</h3><br/>
".addslashes($_POST['ta_comment'])."
<hr/>
unterschrift
";

        $mail->setFrom('admin@ecm-games.com', 'Admin ECM'); //Set who the message is to be sent from
        $mail->addReplyTo('andrey@marchenko.one', 'developer ECM'); //Set an alternative reply-to address
        $mail->addAddress(addslashes($_POST['inp_email']), addslashes($_POST['inp_nickname'])); //Set who the message is to be sent to
        $mail->Subject = 'Заявка на покупку Валюты на сайте ECM-Games.com'; //Set the subject line
        $mail->msgHTML("<h2><%%>Welcome<%%> ".addslashes($_POST['inp_nickname'])."!</h2><br/>".$orderInfo);
        $mail->send();
        //$mail->AltBody = 'test';  //Replace the plain text body with one created manually
        //$mail->addAttachment('template/images/1.jpg');  //Attach an image file

        $mail->setFrom('admin@ecm-games.com', 'Admin ECM'); //Set who the message is to be sent from
        $mail->addReplyTo('andrey@marchenko.one', 'developer ECM'); //Set an alternative reply-to address
        $mail->addAddress('admin@ecm-games.com', 'Admin ECM'); //Set who the message is to be sent to
        $mail->Subject = 'Заявка на покупку Валюты'; //Set the subject line
        $mail->msgHTML($orderInfo);
        $mail->send();

        /*
         * Add record to DB
         * */
        $db->sql_query("INSERT
            orders
            SET
                ordEmail = '".addslashes($_POST['inp_email'])."',
                ordNickname = '".addslashes($_POST['inp_nickname'])."',
                ordComment = '".addslashes($_POST['ta_comment'])."',
                ordType = 1,
                ordDelivery = ".$_POST['s_delivery'].",
                ".(isset($_POST['inp_phone']) && $_POST['inp_phone'] ? "ordPhone = '".addslashes($_POST['inp_phone'])."'," : "")."
                ".(isset($_POST['inp_skype']) && $_POST['inp_skype'] ? "ordSkype = '".addslashes($_POST['inp_skype'])."'," : "")."
                ".(isset($_POST['inp_icq']) && $_POST['inp_icq'] ? "ordICQ = '".addslashes($_POST['inp_icq'])."'," : "")."
                ordCountGold = ".$_POST['inp_zoloto'].",
                ordCost = ".$_POST['inp_zoloto'].",
                ordTotal = ".$_POST['inp_zoloto'].",
                ordGameID = ".$_POST['game_id'].",
                ordServerID = ".$_POST['select_serv'].",
                ordPaymentSystemID = ".$_POST['select_pay']."

        ");

        $url['template'] = array(
            'file' => 'order-thanks.php',
            'name' => 'order-thanks',
            'fullname' => 'template/order-thanks.php',

        );

    }
print_d($url);
print_d($_POST);

print_d("order model end");
