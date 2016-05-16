<?php
/**
 * Created by PhpStorm.
 * User: Andrii
 */

    if (isset($arGame) && count($arGame) > 0)
    {
        $res = $db -> select("SELECT gamID FROM games WHERE gamName='".addslashes($arGame['name'])."'");
        if (!isset($res[0]['gamID']) || $res[0]['gamID']==0)
        {
            $gameID = $db -> insert("INSERT
              INTO games
              SET
                gamName = '".addslashes($arGame['name'])."',
                gamMoney = '".addslashes($arGame['money'])."',
                gamMoneys = '".addslashes($arGame['moneys'])."',
                gamMinCount = ".addslashes($arGame['mincount'])."
            ");
            print_d($gameID);
            if (isset($arGame['servers']) && count($arGame['servers']) > 0)
            {
                foreach($arGame['servers'] as $serv)
                {
                    $serverID = $db -> insert("INSERT
                      INTO gameservers
                      SET
                        gasGameID = $gameID,
                        gasName = '".addslashes($serv['name'])."',
                        gasKurs = '".addslashes($serv['kurs'])."'
                    ");

                }
            }
        }
    }