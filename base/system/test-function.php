<?php

    function print_d($str = "") 
    {
        if(isset($_GET["test"]) || isset($_COOKIE['test']))
        {
            setcookie('test', 1);
            
            print '<pre>';
            if ($str === "") $str = $GLOBALS; 
            if(is_array($str)) print_r($str);
            else var_export($str);
            print '</pre>';
        }
    }