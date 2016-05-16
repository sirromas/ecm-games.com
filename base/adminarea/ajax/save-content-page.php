<?php
    
    //
    //  Load Page (with ID)
    //
    if (isset($_POST))
    {
        if(isset($_POST['cntID']) && $_POST['cntID'] > 0)
        {
            $db->sql_query("UPDATE
                        content
                    
                    SET      
                        cntTitle = '".addslashes($_POST['cntTitle'])."',
                        cntBody = '".addslashes($_POST['cntBody'])."',
                        cntMetaTitle = '".addslashes($_POST['cntMetaTitle'])."',
                        cntMETAKeywords = '".addslashes($_POST['cntMETAKeywords'])."',
                        cntMETADescription = '".addslashes($_POST['cntMETADescription'])."',
                        cntVisible = ".$_POST['cntVisible']."
            
                    WHERE
                        cntID = ".$_POST['cntID']."
                        AND 
                        cntLanguageID = " . ($config -> get_current_language() * 1) . "
            ");
            
            
            $db->sql_query("UPDATE
                        content
                    
                    SET
                        cntFileName = '".addslashes($_POST['cntFileName'])."'
                    
                    WHERE
                        cntID = '".$_POST['cntID']."'
            ");
                
        }
    }
    echo json_encode(array('status'=>"ok"));
    exit;