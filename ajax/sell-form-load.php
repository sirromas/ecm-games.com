<?php

    if (isset($_POST['requestType']))
    {   
        if ($_POST['requestType'] == "game")
        {
            $res = $db->select("
                SELECT
                    *
                FROM
                    games
                WHERE
                    gamActive = 1
            ");
            $res = is_array($res) ? $res : false;
            print(json_encode($res));
            exit;
        } else if ($_POST['requestType'] == "game" || ($_POST['gamID']*1) > 0)
        {
            $res = $db->select("
                SELECT
                    *
                FROM
                    gameservers
                WHERE
                    gasActive = 1
                    AND
                    gasGameID = ".($_POST['gamID']*1)."
            ");
            $res = is_array($res) ? $res : false;
            print(json_encode($res));
            exit;
        }
    }
    
