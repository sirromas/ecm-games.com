<?php
    class MySQL extends DBConfig
    {

        private $CONN, $DBASE, $USER, $PASS, $SERVER, $CHARSET, $LOGLEVEL, $TIME;

//      LOGLEVEL = 0;   // 'none'
//      LOGLEVEL = 1:   // 'work'
//      LOGLEVEL = 2;   // 'debug'
//      LOGLEVEL = 3;   // 'all'

        public function __construct() 
        {
            parent::__construct();
            $this -> CONN = '';
            $this -> DBASE = $this -> db['database'];
            $this -> USER = $this -> db['userbase'];
            $this -> PASS = $this -> db['password'];
            $this -> SERVER = $this -> db['host'];
            $this -> LOGLEVEL = $this -> db['loglevel'];
            $this -> CHARSET = isset($this -> db['charset']) ? $this -> db['charset'] : 'utf8';
            $this -> TIME;
        }

        function getmicrotime()
        {
            list($usec, $sec) = explode(" ",microtime()); 
            return ((float)$usec + (float)$sec); 
        }

        function mysql_log($text, $sql = "") 
        {
            if ($this->LOGLEVEL == 0 || 
                    ($text == '' && 
                        ($this->LOGLEVEL == 1 || 
                            ($this->LOGLEVEL == 2 && preg_match('/^(SELECT|SHOW|DESCRIBE|EXPLAIN|$)/i', $sql)
                            )
                        )
                    )
                )

                return ;

            $logpath = 'mysql-errors';

            $no = mysql_errno();
            $msg = mysql_error();
            
            $msg = ($text == '' ? '-' : $text)." ".($no == 0 ? '-' : $no)." ".($msg == '' ? '-' : $msg)." Time:".number_format($this->TIME, 6)." Url:".$_SERVER['REQUEST_URI']."\tRef:" . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '') ." SQL:".preg_replace('/\s+/', ' ', $sql)."\n";
            $hash = md5(time().$msg);
            $msg = "[".date('Y-m-d H:i:s')."] $hash ".$msg;


            if ($text != '' && ENVIRONMENT === 'development') 
            {
                throw new Exception($msg);
            }

            error_log($msg, 3, $logpath.'.log');
            
            $msg = "<hash id='$hash'>\n\t<sql>$sql</sql>\n\t<cookie>".(count($_COOKIE) ? var_export($_COOKIE, true) : '')."</cookie>\n\t<post>".(count($_POST) ? var_export($_POST, true) : '')."</post>\n\t<get>".(count($_GET) ? var_export($_GET, true) : '')."</get>\n</hash>\n";
            error_log($msg, 3, $logpath.'-details.log');
        }

        function safe_free_result($result, $sql)
        {
            $results = @mysql_free_result($result);

            if ($results == false)
                $this->mysql_log('mysql_free_result', $sql);
        }

        function init ()
        {
            global 
                $websiteLogLevel, $websiteAddDir;

            $user = $this->USER;
            $pass = $this->PASS;
            $server = $this->SERVER;
            $dbase = $this->DBASE;
            //$this->LOGLEVEL = (defined('CONFIGURATION_DB_LOGLEVEL') ? CONFIGURATION_DB_LOGLEVEL : 1);
            
            $conn = @mysql_connect($this->SERVER, $this->USER, $this->PASS, true);
                    
            if (!$conn) 
            {
                $logpath = 'system/logs/mysqlerroroccured.txt';
        
                if (!file_exists($logpath)) 
                {
                    $somecontent1 = date("l dS F Y h:i:s A").' TimeStamp: '.microtime();
                    $handle = fopen($logpath, 'w');
                    fwrite($handle, $somecontent1);
                    fclose($handle);
                    
                    if (file_exists($logpath)) 
                    {
                        $somecontent2 = file($logpath);
                        $somecontent2 = $somecontent2[0];

                        if ($somecontent2 == $somecontent1)
                        {
                            mail("admin@wd-studio.in.ua"
                                , "ailTOs", "VPS(",$_SERVER['HTTP_HOST']."): mysql is down", "From: <admin@wd-studio.in.ua>");
                        }
                    }
                }

                
                $this->mysql_log('Connection attempt failed');
                throw new Exception('Connection attempt failed');
            }

            if (!@mysql_select_db($this->DBASE, $conn)) 
            {
                $this->mysql_log('Dbase Select failed');
                throw new Exception('Dbase Select failed');
            }

            $this->CONN = $conn;

            
            if (isset($this->DBASE))
            {
                $this->sql_query("SET NAMES " . $this->CHARSET);
            }

            return true;
        }



        function getAutoIncrement ($table)
        {

            $this->TIME = 0;

            if (empty($this->CONN))
            {
                $this->mysql_log('No connection', $sql);
                return false;
            }

            $sql = "SHOW TABLE STATUS FROM `".$this->DBASE."` LIKE '$table'";
            $conn = $this->CONN;
            $this->TIME = $this->getmicrotime();
            $results = mysql_query($sql, $conn);
            $this->TIME = $this->getmicrotime() - $this->TIME;
            

            if (!$results || empty($results))
            {
                $this->mysql_log('No results', $sql);
                return false;
            }
                        
            $count = 0;
            $data = array();


            while ($row = mysql_fetch_array($results))
            {
                $data[$count] = $row;
                $count++;
            }


            $this->safe_free_result($results, $sql);

            $this->mysql_log('', $sql);
            return $data[0]['Auto_increment'];
        }

        function select ($sql = "", $param = "")
        {
            if (empty($sql)) {
                return false;
            }

            $this->TIME = 0;

            if (!preg_match("/^\s*select/is", $sql)) 
            {
                $this->mysql_log('Wrong function silly', $sql);
                return false;
            }

            if (empty($this->CONN))
            {
                $this->mysql_log('No connection', $sql);
                return false;
            }

            $_sql = preg_replace('/^#.*$/m', ' ', $sql);

            if ($param != 'skip') $_sql = preg_replace('/\sfrom\s+(.+?)((left|inner|right)\s+join|where)/is', ' FROM (\\1) \\2', $_sql);
            
            if ($_sql != '')
            {
                $sql = $_sql;
            }
            

            $conn = $this->CONN;
            $this->TIME = $this->getmicrotime();
            $results = mysql_query($sql, $conn);
            $this->TIME = $this->getmicrotime() - $this->TIME;
            

            if (!$results || empty($results))
            {
                $this->mysql_log('No results', $sql);
                return false;
            }
                        
            $count = 0;
            $data = array();


            while ($row = mysql_fetch_array($results))
            {
                $data[$count] = $row;
                $count++;
            }


            $this->safe_free_result($results, $sql);

            $this->mysql_log('', $sql);
            return $data;
        }

        function select_resource ($sql = "", $param = "")
        {
            if (empty($sql))
            {
                return false;
            }

            $this->TIME = 0;

            if (!preg_match("/^\s*select/is", $sql))
            {
                $this->mysql_log('Wrong function silly', $sql);
                return false;
            }

            if (empty($this->CONN))
            {
                $this->mysql_log('No connection', $sql);
                return false;
            }
            
            if ($param != 'skip') $sql = preg_replace('/\sfrom\s+(.+?)(left|inner|right)\s+join/is', ' FROM (\\1) \\2 JOIN', $sql);

            $conn = $this->CONN;
            $this->TIME = $this->getmicrotime();
            $results = mysql_query($sql, $conn);
            $this->TIME = $this->getmicrotime() - $this->TIME;
            
            if (!$results || empty($results))
            {
                $this->mysql_log('No results', $sql);
                return false;
            }

            return $results;  
        }


        function sql_query_result ($sql="")
        {
            if(empty($sql)) {
                return false;
            }

            $this->TIME = 0;

            if (empty($this->CONN))
            {
                $this->mysql_log('No connection', $sql);
                return false;
            }

            $conn = $this->CONN;
            $this->TIME = $this->getmicrotime();
            $results = mysql_query($sql, $conn);
            $this->TIME = $this->getmicrotime() - $this->TIME;

            if (!$results || empty($results))
            {
                $this->mysql_log('No results', $sql);
                return false;
            }
                        
            $count = 0;
            $data = array();

            while ( $row = mysql_fetch_array($results))
            {
                $data[$count] = $row;
                $count++;
            }

            $this->safe_free_result($results, $sql);

            $this->mysql_log('', $sql);
            return $data;
        }

        function insert ($sql="")
        {
            if (empty($sql)) 
            {
                return false;
            }

            $this->TIME = 0;

            if (!preg_match("/^\s*(insert|replace)/is",$sql)) 
            {
                $this->mysql_log('Wrong function silly', $sql);
                return false;
            }

            if (empty($this->CONN))
            {
                $this->mysql_log('No connection', $sql);
                return false;
            }

            $conn = $this->CONN;
            $this->TIME = $this->getmicrotime();
            $results = mysql_query($sql,$conn);
            $this->TIME = $this->getmicrotime() - $this->TIME;

            if (!$results)
            {
                $this->mysql_log('No results', $sql);
                return false;
            }
   
            $results = mysql_insert_id($conn);

            $this->mysql_log('', $sql);
            return $results;
        }
   
        function sql_query ($sql = "")
        {
            if (empty($sql))
            {
                return false;
            }
   
            $this->TIME = 0;

            if (empty($this->CONN)) 
            {
                $this->mysql_log('No connection', $sql);
                return false;
            }

            $conn = $this->CONN;
            $this->TIME = $this->getmicrotime();
            $results = mysql_query($sql, $conn);
            $this->TIME = $this->getmicrotime() - $this->TIME;

            if(!$results) 
            {
                $this->mysql_log('No results', $sql);
                return false;
            }

            $this->mysql_log('', $sql);
            return $results;
        }
   
        function sql_cnt_query ($sql="")
        {
            if (empty($sql))
            {
                return false;
            }
   
            $this->TIME = 0;

            if (empty($this->CONN))
            {
                $this->mysql_log('No connection', $sql);
                return false;
            }
   
            $conn = $this->CONN;
            $this->TIME = $this->getmicrotime();
            $results = mysql_query($sql, $conn);
            $this->TIME = $this->getmicrotime() - $this->TIME;

            if (!$results || empty($results))
            {
                $this->mysql_log('No results', $sql);
                return false;
            }
   
            $count = 0;
            $data = array();
   
            while ( $row = mysql_fetch_array($results))
            {
                $data[$count] = $row;
                $count++;
            }
   
            $this->safe_free_result($results, $sql);

            $this->mysql_log('', $sql);
            return $data[0][0];
        }
    }

    $db = new MySQL;
    $db -> init();

//  $db->sql_query ("SET NAMES 'utf8'");
//  $db->sql_query ("SET CHARSET 'utf8'");
//  print_r($db->sql_query_result ("SHOW VARIABLES LIKE '%character%'"));
