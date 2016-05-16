<?php

print_d("admin default model start");

    //default model
  $tTitle = false;

  $arAdmin = array(
    'development' => array(
      'Name' => 'Development',
      'templatefile' => '#',
      'icon' => 'bullhorn',
      'visible' => true,
      'child' => array(
        'dashboard' => array(
          'Name' => 'Dashboard',
          'templatefile' => 'default.php',
          'icon' => 'dashboard',
          'visible' => true,
          'child' => array(),
        ),
        'uielements' => array(
          'Name' => 'UI Elements',
          'templatefile' => 'ui.php',
          'icon' => 'venus',
          'visible' => true,
          'child' => array(),
        ),
        'datatable' => array(
          'Name' => 'Data Tables',
          'templatefile' => 'table.php',
          'icon' => 'bolt',
          'visible' => true,
          'child' => array(),
        ),
        'forms' => array(
          'Name' => 'Forms',
          'templatefile' => 'forms.php',
          'icon' => 'code',
          'visible' => true,
          'child' => array(),
        ),
        'multy' => array(
          'Name' => 'Second Level',
          'templatefile' => '#',
          'icon' => 'sitemap',
          'visible' => true,
          'child' => array(
            'menu31' => array(
              'Name' => 'Link 1',
              'templatefile' => '#',
              'icon' => '',
              'visible' => true,
              'child' => array(),
            ),
            'menu32' => array(
              'Name' => 'Link 2',
              'templatefile' => '#',
              'icon' => '',
              'visible' => true,
              'child' => array(),
            ),
          ),
        ),
      ),
    ),
    'blank' => array(
      'Name' => 'Blank Page',
      'templatefile' => 'blank.php',
      'icon' => '',
      'visible' => true,
      'child' => array(),
    ),
    'config' => array(
      'Name' => 'Settings',
      'templatefile' => 'configs.php',
      'icon' => 'cogs',
      'visible' => true,
      'child' => array(),
    ),
    'content' => array(
      'Name' => 'Content Manager',
      'templatefile' => 'content.php',
      'icon' => 'dashcube',
      'visible' => true,
      'child' => array(),
    ),
  );

    if(strpos($content->requestedURL, 'ajax') !== false)
    {
        print_d($content);
        require($content->requestedURL);
        exit;
    }

print_d("admin default model end");
     