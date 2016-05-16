<?php

    /* ---------------------------------------
     * Configuration file
     * ---------------------------------------
     */
    
    date_default_timezone_set('Europe/Amsterdam');

    class DBConfig
    {
        /*----------------------------
        *   Database Configuration
        *-----------------------------
        */
//      LOGLEVEL = 0;   // 'none'
//      LOGLEVEL = 1:   // 'work'
//      LOGLEVEL = 2;   // 'debug'
//      LOGLEVEL = 3;   // 'all'

        public $db = array();
        
        public function __construct()
        { 
            $this -> db = array(
                'host'     => "localhost",
                'database' => "mycodebu_games",
                'userbase' => "mycodebu_moneych",
                'password' => "aK6SKymc*",
                'loglevel' => 1,
                'charset'  => 'utf8',
            );
        }
    }
    
    $dbconfig = new DBConfig();

    require_once ($system_path.'/mysql-class.php');
    require_once ($system_path.'/function.php');
    require_once ($system_path.'/content-class.php');
    
    class Config extends DBConfig
    {
        public $codepage = "utf-8";
        public $language_list = array(1 => 'en', 2 => 'ru', 3 => 'ua');
        public $languages = array(); 
        public $default_language = 2;
        private $current_language = false;
        
        public $template = "";
        private $template_fullpath = "";
        private $model_fullpath = "";
        
        public $content = array();
        public $default_controller = "default";
        public $default_model = "default";

        public $admin_template = "template-admin";
        public $default_admin_controller = "admin_controller";
        public $default_admin_model = "admin_default";
        private $admin_template_fullpath = "";
        private $admin_model_fullpath = "";
        private $admin_controller_fullpath = "";

        public function __construct()
        {
            global $db;
            global $admin_folder, $view_folder, $model_folder;

            $tLang = false;
            $tLang = $db -> select("
                SELECT * FROM languages ORDER BY lngID
            ");
               
            if (is_array($tLang)) 
            {
                foreach($tLang as $lng)
                {
                    $this->languages[$lng['lngID']] = array('lngID' => $lng['lngID'],'lngName' => $lng['lngName'], 'lngShort' => $lng['lngShort'], 'lngUrl' => $lng['lngUrl'], 'lngActive' => $lng['lngActive'], );
                    if ($lng['lngActive'] > 0)
                    {
                        $this -> language_list[$lng['lngID']] = $lng['lngUrl'];
                    }
                }
            }
            
            /*-----------------------------------------
            *    Configure template
            * -----------------------------------------
            */

            //TODO: load template name ($this -> template) from DB
            
            $this -> template_fullpath = str_replace('//', "/", trim($view_folder, '/\\')."/".trim($this -> template, '/\\')."/");

            $this -> model_fullpath = trim($model_folder, '/\\')."/";

            $this -> admin_template_fullpath = str_replace('//', "/", 
                trim($admin_folder, '/\\') . "/" . trim($this -> admin_template, '/\\') . "/"
            );

            $this -> admin_model_fullpath = trim($admin_folder, '/\\') . "/" . trim($model_folder, '/\\')."/";
            $this -> admin_controller_fullpath = trim($admin_folder, '/\\') . "/";

        }
        
        public function get_admin_template_fullpath()
        {
            return $this -> admin_template_fullpath;
        }

        public function get_admin_model_fullpath()
        {
            return $this -> admin_model_fullpath;
        }

        public function get_admin_controller_fullpath()
        {
            return $this -> admin_controller_fullpath;
        }

        public function get_template_fullpath()
        {
            return $this -> template_fullpath;
        }

        public function get_model_fullpath()
        {
            return $this -> model_fullpath;
        }

        public function get_current_language()
        {
            if ($this -> current_language === false)
            {
                $this -> current_language = $this -> default_language;
            }
            return $this -> current_language;
        }
        
        public function set_current_language($n)
        {
            $this -> current_language = $n*1;
        }
    }

    $config = new Config();
    $config -> codepage = "utf-8";
    $config -> default_language = 2;
    $config -> template = "bootstrap-theme";
    
    $config -> default_controller = 'default';
    $config -> default_model = 'default';

    $config -> content['header'] = 100;
    $config -> content['internal'] = 200;
    $config -> content['footer'] = 300;

    $config -> content['mainpage'] = 21;

    $content = new Contents();
    
    /* translate table */
    $translate_table = array(
        array(1 => 'Jobs', 2 => 'Ð’Ð°ÐºÐ°Ð½Ñ�Ð¸Ð¸', 3 => 'Ð’Ð°ÐºÐ°Ð½Ñ�Ñ–Ñ—'),

    );