<?php
    
        //
        //  Load All Pages (short information)
        //
        
        
        // Reorder menu item
        $items = $db->select("SELECT
                cntID, cntParentID, cntOrder
                
            FROM
                content
    
            WHERE
                cntLanguageID = " . ($config -> get_current_language() * 1) . "
            
            ORDER BY
                cntParentID, cntOrder, cntTitle
        ");
        
        $prevParentID = $items[0]["cntParentID"];
        $iOrder = 0;
        
        for ($i = 0; $i < count($items); $i++) 
        {
            $item = $items[$i];
            if ($item["cntParentID"] == $prevParentID) 
            {
                $iOrder++;
            } else {
                $iOrder = 1;
                $prevParentID = $item["cntParentID"];
            }
            
            if ($item["cntOrder"] != $iOrder) 
            {
                $db->sql_query("UPDATE
                    content
                    
                    SET
                        cntOrder = $iOrder
                        
                    WHERE
                        cntID = ".$item['cntID']."
                "); 
            }   
        }
        
        $arPages = getChildContent();
        echo json_encode($arPages);
        
        //print_d($items);
        exit;
 
        function getChildContent($cntID = 0, $cntLevel = 0)
        {
            global $db, $config;
            $arPages = array();
            // Getting Pages from DB
            $items = $db->select("SELECT
                    cntID, cntParentID, cntTitle, cntVisible, cntOrder
                
                FROM
                    content
                
                WHERE
                    cntLanguageID = " . ($config -> get_current_language() * 1) . "
                    AND
                    cntParentID = $cntID
                
                ORDER BY
                    cntOrder
            ");
            
            if (count($items) < 1) return false;
            foreach($items as $Page)
            {
                $Page['cntLevel'] = $cntLevel;
                $arPages[] = $Page;
                $bHasChild = getChildContent($Page['cntID'], $cntLevel + 1);
                if ($bHasChild) 
                {
                    foreach($bHasChild as $p)
                    {  
                        $arPages[] = $p;
                    }
                }
            }
            return $arPages;
        }