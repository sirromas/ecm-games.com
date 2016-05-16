<?php
    
    //
    //  Load Page (with ID)
    //
       
    $item = $db->select("SELECT
            *
        
        FROM
            content
        
        WHERE
            cntLanguageID = " . ($config -> get_current_language() * 1) . "
            AND
            cntID = " . ($_GET['cntID'] * 1) . "
    ");

    foreach($item[0] as $key => $i )
    {
        if ($key*1 === $key) 
        {
            unset($item[0][$key]);
            continue;
        }
        if(empty($item[0][$key]) || is_null($item[0][$key]) || $item[0][$key] == "") $item[0][$key] = "";
    }
    echo json_encode($item);
    exit;