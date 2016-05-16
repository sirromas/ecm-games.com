<?php
    require_once('system/mailer/PHPMailerAutoload.php');
    $mail = new PHPMailer; //Create a new PHPMailer instance
    $mail -> CharSet = 'utf-8';

    if (isset($_POST['requestType']))
    {   
        if ($_POST['requestType'] == "save")
        {
            $result = $db->insert("INSERT INTO
                        orders
                    
                    SET      
                        ordType = '2',
                        ordEmail = '".addslashes($_POST['ordEmail'])."',
                        ordNickname = '".addslashes($_POST['ordNickname'])."',
                        ordComment = '".addslashes(nl2br($_POST['ordComment']))."',
                        ordSkype = '".addslashes($_POST['ordSkype'])."',
                        ordPhone = '".addslashes($_POST['ordPhone'])."',
                        ordICQ = '".addslashes($_POST['ordICQ'])."',
                        ordCountGold = '".addslashes($_POST['ordCountGold']*1)."',
                        ordGameID = '".addslashes($_POST['ordGameID']*1)."',
                        ordServerID = '".addslashes($_POST['ordServerID']*1)."'
            ");

            $res = $result > 0 ? $result : false;

            $addUserText = "
<p><%%>Thank you for your inquiry!<%%></p>
<p><%%>In the near future you will be contacted by our operator for matching of your request<%%></p>
";
            $orderInfo = "
<h3><%%>Order<%%>: ".htmlspecialchars($result)."</h3><br/>
<strong><%%>Game<%%>:</strong>".htmlspecialchars($_POST['game_name'])."<br/>
<strong><%%>Server<%%>:</strong>".htmlspecialchars($_POST['server_name'])."<br/>
<strong><%%>Gold count<%%>:</strong>".htmlspecialchars($_POST['ordCountGold'])."<br/>
<hr/><h3><%%>Contact info<%%>:</h3><br/>
<strong><%%>Name<%%>:</strong>".htmlspecialchars($_POST['ordName'])."<br/>
<strong><%%>Nick<%%>:</strong>".htmlspecialchars($_POST['ordNickname'])."<br/>
".(isset($_POST['ordEmail']) && $_POST['ordEmail'] ? "<strong><%%>Email<%%>:</strong>".htmlspecialchars($_POST['ordEmail'])."<br/>" : "")."
".(isset($_POST['ordPhone']) && $_POST['ordPhone'] ? "<strong><%%>Phone<%%>:</strong>".htmlspecialchars($_POST['ordPhone'])."<br/>" : "")."
".(isset($_POST['ordSkype']) && $_POST['ordSkype'] ? "<strong><%%>Skype<%%>:</strong>".htmlspecialchars($_POST['ordSkype'])."<br/>" : "")."
".(isset($_POST['ordICQ']) && $_POST['ordICQ'] ? "<strong><%%>ICQ<%%>:</strong>".htmlspecialchars($_POST['ordICQ'])."<br/>" : "")."
<hr/>
".(isset($_POST['ordComment']) && $_POST['ordComment'] != "" ? "<h3><%%>Additional info<%%>:</h3><br/>
".addslashes(nl2br($_POST['ordComment']))."
<hr/>
" : "" )."
";

            $mail->setFrom('admin@ecm-games.com', 'Admin ECM'); //Set who the message is to be sent from
            $mail->addReplyTo('andrey@marchenko.one', 'developer ECM'); //Set an alternative reply-to address
            $mail->addAddress(addslashes($_POST['ordEmail']), addslashes($_POST['ordNickname'])); //Set who the message is to be sent to
            $mail->Subject = 'Заявка на продажу Валюты на сайте ECM-Games.com'; //Set the subject line
            $mail->msgHTML("<h2><%%>Welcome<%%> ".addslashes($_POST['inp_nickname'])."!</h2><br/>".$addUserText.$orderInfo);
            $mail->send();
            //$mail->AltBody = 'test';  //Replace the plain text body with one created manually
            //$mail->addAttachment('template/images/1.jpg');  //Attach an image file

            $mail->setFrom('admin@ecm-games.com', 'Admin ECM'); //Set who the message is to be sent from
            $mail->addReplyTo('andrey@marchenko.one', 'developer ECM'); //Set an alternative reply-to address
            $mail->addAddress('admin@ecm-games.com', 'developer ECM'); //Set who the message is to be sent to
            $mail->Subject = 'Заявка на продажу Валюты'; //Set the subject line
            $mail->msgHTML($orderInfo);
            $mail->send();

            print(json_encode($res));
            exit;
        } 
    }
    
