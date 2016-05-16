<?php
    
    //
    //  Load Page (with ID)
    //
    
    if (isset($_GET))
    {
        $cntID = $db->getAutoIncrement ("content");
        foreach($config->languages as $lang)
        {
            $result = $db->insert("INSERT INTO
                        content
                    
                    SET      
                        cntID = '".$cntID."',
                        cntParentID = '".addslashes($_GET['cntParentID'])."',
                        cntTitle = '".addslashes($_GET['cntTitle'])."',
                        cntFileName = '".addslashes($_GET['cntFileName'])."',
                        cntLanguageID = " . ($lang['lngID'] * 1) . ",
                        cntVisible = ".$_GET['cntVisible']."
            ");
        }

    }
    echo json_encode(array('cntID'=>$cntID));
    exit;