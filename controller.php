<?php

//print_d($url);
//print_d($config);
//print_d($content);

print_d("controller start");

    if (isset($url['controller']))
    {
        require file_translate($url['controller']['fullname']);
        print_d("controller end");
        exit;
    }

    // load model
    if (isset($url['model']))
    {
        require file_translate($url['model']['fullname']);
    } else {
        require file_translate('/' . $model_folder . '/' . $config -> default_model.'.php');
    }

    // load view
    if (isset($url['template']))
    {
        require file_translate($url['template']['fullname']);
    } else {
        require file_translate($config -> get_template_fullpath());
    }

print_d("controller end");

     