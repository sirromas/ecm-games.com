<?php
/**
 * Created by PhpStorm.
 * User: Andrii
 */

    $res = $db -> select("
        SELECT 
            * 
        FROM 
            games 
        INNER JOIN
            gamescontent
        ON
            gmcGameID = gamID
            AND
            gmcContentID = '".$content->arPage['cntID']."'
    ");
    
    if (isset($res) && count($res) > 0)
    {
        $arGame["id"] = $res[0]["gamID"];
        $arGame["name"] = $res[0]["gamName"];
        $arGame["money"] = $res[0]["gamMoney"];
        $arGame["moneys"] = $res[0]["gamMoneys"];
        $arGame["mincount"] = $res[0]["gamMinCount"];

        $ress = $db -> select("SELECT * FROM gameservers WHERE gasGameID=".$arGame["id"]);

        if (isset($ress) && count($ress) > 0)
        {
            foreach($ress as $serv)
            {
                $arGame['servers'][] = array(
                    'id' => $serv['gasID'],
                    'name' => $serv['gasName'],
                    'kurs' => $serv['gasKurs']
                );
            }
        }
    }
    /* load Delivery && payment*/

    $resd = $db -> select("SELECT * FROM delivery");
    if(isset($resd) && count($resd) > 0 )
    {
        foreach($resd as $deliv)
        {
            $arDelivery[] = array("id" => $deliv['delID'], "name" => $deliv['delName']);
        }

    }

    $curlSession = curl_init();
    curl_setopt($curlSession, CURLOPT_URL, 'http://fxrates.ru.forexprostools.com/index.php?force_lang=7&pairs_ids=2124;2186;2208;&header-text-color=%23FFFFFF&curr-name-color=%230059b0&inner-text-color=%23000000&green-text-color=%232A8215&green-background=%23B7F4C2&red-text-color=%23DC0001&red-background=%23FFE2E2&inner-border-color=%23CBCBCB&border-color=%23cbcbcb&bg1=%23F6F6F6&bg2=%23ffffff&bid=hide&ask=hide&last=show&open=hide&high=hide&low=hide&change=hide&change_in_percents=hide&last_update=hide');
    curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);

    $dataParsed = curl_exec($curlSession);
    curl_close($curlSession);
    preg_match("/(<table.+?\/table>)/is",$dataParsed, $table);
    preg_match_all("/direction:ltr;\">(\d+\.\d+?)<\/td>/is", $table[1], $kurses );

    if (isset($kurses[1]) && is_array($kurses[1]) && count($kurses[1]) > 1 )
    {
        $arCurr = array(
            'usd' => 1,
            'eur' => $kurses[1][0],
            'rur' => $kurses[1][1],
            'uah' => $kurses[1][2],
        );
    }

    $arVal = array("usd"=>"$", "eur"=>"&euro;", "uah"=>"UAH", "rur"=>"RUB");
    $resp = $db -> select("SELECT * FROM payments WHERE payActive=1");
    if(isset($resp) && count($resp) > 0 )
    {
        foreach($resp as $pays)
        {
            $arPaysystem[] = array("id" => $pays['payID'], "name" => $pays['payName'], "kurs"=>$arCurr[$pays['payVal']], "val"=>$arVal[$pays['payVal']]);
        }

    }
