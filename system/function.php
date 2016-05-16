<?php
    require_once($system_path."/test-function.php");

    function fileexists($file)
    {
        $ps = explode(":", ini_get('include_path'));
        foreach($ps as $path)
        {
            if(file_exists($path.'/'.$file)) return true;
        }
        if(file_exists($file)) return true;
        return false;
    }

    function get_filename($file_name)
    {
        $newfile = basename($file_name);
        if (strpos($newfile,'\\') !== false)
        {
            $tmp = preg_split("[\\\]",$newfile);
            $newfile = $tmp[count($tmp) - 1];
            return($newfile);
        }else{
            return($file_name);
        }
    }

    

    function translate($str)
    {
        global $translate_table, $db, $config;
        $str = trim($str);
        $str = str_replace('<%%>', '', $str);
        if(empty($str)) return;
        if (is_array($translate_table))
        {
            $bFound = false;
            foreach($translate_table as $tr_el)
            {
                if ($tr_el[1] == $str)
                {
                    $bFound = true;
                    //print_d();
                    $result = $tr_el[$config->get_current_language()];
                    break;
                }
            }
        }

        if ($bFound) return $result;

        $tSearch = $db -> select('
            SELECT
                *
            FROM
                messages
            WHERE
                msgMD5 = \'' . md5($str) . '\'
                AND
                msgLanguageID = ' . $config -> get_current_language() . '
        ');
        if (empty($tSearch))
        {
            /** insert into db this frage for all languages */
            $msgID = false;
            foreach($config->language_list as $key => $lang)
            {
                if (!$msgID)
                {
                    $msgID = $db->insert('
                        INSERT INTO
                            messages (msgText, msgMD5, msgLanguageID)
                        VALUES
                            (\'' . addslashes($str) . '\', \'' . md5($str) . '\', ' . $key . ')
                    ');
                }else{
                    $msgID = $db->insert('
                        INSERT INTO
                            messages (msgID, msgText, msgMD5, msgLanguageID)
                        VALUES
                            (' . $msgID . ', \'' . addslashes($str) . '\', \'' . md5($str) . '\', ' . $key . ')
                    ');

                }

            }
        }else{
            $str = $tSearch[0]['msgText'];
        }
        /* search in db*/
        return $str;
    }

    function file_translate($file)
    {
        global $config;
        global $db;
        global $languages;
        global $websiteTranslateVersion;

        $includepath = explode(PHP_OS == "WINNT" ? ';' : ':', ini_get("include_path"));

        // Find original file
        $path = "";
        foreach ($includepath as $findpath)
        {
            $findpath = str_replace("\\", '/', $findpath);

            if (substr($findpath, -1) != '/') { $findpath .= '/'; }

            if (is_file($findpath.$file))
            {
                $path = $findpath;
                break;
            }
        }

        if ($path == "")
        {
            return $file;
        }

        $file = $path.$file;
        $md5_file = md5($file.filesize($file).filemtime($file));

        if (is_file($file.'.'.$config->languages[$config->get_current_language()]['lngShort'].'.'.$md5_file))
        {
            return $file.'.'.$config->languages[$config->get_current_language()]['lngShort'].'.'.$md5_file;
        } else {

            $filename = mb_ereg_replace('(.+\/)(.*)', '\\2', $file);
            $path = mb_ereg_replace('(.+\/)(.*)', '\\1', $file);
            $d = dir($path);
            $files = array();

            while (false !== ($entry = $d->read()))
            {
                if ((strlen($entry) == (strlen($filename)+36)) && (strpos($entry, $filename.'.'.$config->languages[$config->get_current_language()]['lngShort'].'.') === 0))
                {
                    $files[] = $path.$entry;
                }
            }
            $d->close();

            foreach ($files as $filename)
            {
                if (is_file($filename))
                {
                    @unlink($filename);
                }
            }

            $fp = fopen($file, "r");

            if ($fp)
            {

                if (filesize($file) > 0)
                {
                    $data = fread ($fp, filesize($file));
                } else {
                    $data =  '';
                }

                fclose ($fp);

                preg_match_all("/(<%%>.+?<%%>)/s", $data, $lines);

                if (count($lines[1]) > 0)
                {
                    foreach ($lines[1] as $line)
                    {

                        $data = str_replace($line, translate($line), $data);
                    }

                    $file = $file.'.'.$config->languages[$config->get_current_language()]['lngShort'].'.'.$md5_file;
                    $fp = fopen($file, "w");

                    if (!fwrite($fp, $data))
                    {
                        print "Cannot write to file ($file)";
                        exit;
                    }

                    fclose ($fp);
                    return $file;

                } else {
                    return $file;
                }

            } else {
                print "Translate Error<br/>\nCan't open ($file)<br/>\n";
                return "Error include ($file)";
                exit;
            }

        }

    }


    function getNewURL($newLang = "-", $newUrl = "-")
    {
        global $config, $url;

        if ($newLang === "-" && $newUrl === "-") return $config -> content['full_url']['path'] . (isset($config -> content['full_url']['query']) && !empty($config -> content['full_url']['query']) ? "?" . $config -> content['full_url']['query'] : '');

        $newPath = "/";

        if ($newLang === "-")
        {
            /** Use data from $config */
            $newPath .= $url["language"]['lngUrl'] . "/";
        }else{
            $foundLang = false;
            foreach($config->language_list as $keyLang => $sLang)
            {
                if ($config->languages[$keyLang]['lngActive'] > 0)
                {
                    if($newLang === $sLang || $newLang === $keyLang)
                    {
                        $newPath .= $sLang . "/";
                        $foundLang = true;
                        break;
                    }
                }
            }

            if (!$foundLang)
            {
                $newPath = $newPath . $config->language_list($config->get_current_language()) . "/";
            }
        }

        if ($newUrl === "-")
        {
            /** Use data from $config */
            $newPath .= $config -> content['full_url']['path'];
        }else{
            $newPath .= $newUrl;
        }

        return rtrim($newPath, '/') . ( 
            isset($config -> content['full_url']["query"]) 
            && 
            !empty($config -> content['full_url']["query"]) 
            && 
            $config -> content['full_url']["query"]!= ""  
            && 
            $config -> content['full_url']["query"]!= "/" 
            ? 
            "?" . $config -> content['full_url']["query"] 
            : 
            ""
        );
    
    }