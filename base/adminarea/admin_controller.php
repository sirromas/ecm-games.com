<?php
//print_d($GLOBALS);

print_d("admin controller start");

    // load model
    if (isset($url['model']))
    {
        require file_translate($url['model']['fullname']);
    } else {
        require file_translate($config -> admin_model_fullpath . $config -> default_admin_model.'.php');
    }

    // load view
    if (isset($url['template']))
    {
        require file_translate($url['template']['fullname']);
    } else {
        require file_translate($config -> get_template_fullpath());
    }

print_d("admin controller end");

     