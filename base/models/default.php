<?php

print_d("default model start");

    //default model
    $tTitle = false;
    if(strpos($content->requestedURL, 'ajax') !== false)
    {
        require($content->requestedURL);
        exit;
    }



print_d("default model end");
     