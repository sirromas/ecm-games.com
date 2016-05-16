<?php
    //
    //  Content System
    //  version 1.0
    //
    

/*
    function generateMenuTree($iID, $hrefPrefix, $ignoreIDs = false, $_generatedMenuTree = false, $ulClass = "", $treeStyle = "full", $maxDeepLevel = 1000, $maxItemLength = 0) 
    function generateMenuTree2($iID, $hrefPrefix, $ignoreIDs = false, $_generatedMenuTree = false, $ulClass = "", $treeStyle = "full", $maxDeepLevel = 1000, $hintTitle = false, $iLimitAmountOfItems = -1, $tabs = "") 
    function generateMenuSiteMapTree($iID, $hrefPrefix, $ignoreIDs = false, $_generatedMenuTree = false, $ulClass = "", $treeStyle = "full", $maxDeepLevel = 1000, $hintTitle = false, $iLimitAmountOfItems = -1) 
    function getTreeForCat($categoryID)
    function contentHasChildPages($iID) 
    function contentGetPageURL($cntID, $bAbsolutePath = false, $bVisibleOnly = true)
*/
    $url = false; 
    $requestedUri = "/";

    class Contents 
    {
/*
        function setContent($url = "")
        function setWelcomePage() 
        function isWelcomePage() 
        function getMainMenuID() 
        function getFooterMenuID() 
*/
        var $currentLevel   = 0;
        var $requestedURL   = "";
        var $pathInfo       = "";
        var $realURL        = "";
        var $arFileNames    = array();
        var $arIDs          = array();
        var $arTitles       = array();
        var $arPage         = array();
        var $bWelcome       = false;
        var $bError         = false;

        var $arMainMenu     = array(); 
        
        var $bRedirect      = false;
        var $redirectURL    = "";
        
        var $AdminView      = false;
        
        function Contents() 
        {
            global $db;
            global $config;
            global $url, $requestedUri;
             
            $config -> content['header'] = (isset($config -> content['header']) ? $config -> content['header'] : 100);
            $config -> content['internal'] = (isset($config -> content['internal']) ? $config -> content['internal'] : 200);
            $config -> content['footer'] = (isset($config -> content['footer']) ? $config -> content['footer'] : 300);

            $config -> content['mainpage'] = (isset($config -> content['mainpage']) ? $config -> content['mainpage'] : 21);

            $items = $db -> select("SELECT
                    *

                FROM
                    content
                    
                WHERE
                    cntLanguageID = '" . ($config -> get_current_language() * 1) . "'
                    AND
                    cntVisible = 1
                    AND
                    cntParentID = '" . ($config -> content['header'] * 1) . "'
                ORDER BY 
                    cntOrder
            ");
            
            $this->arMainMenu = $items;
            //todo: delete content['mainmenu']
            //$config -> content['mainmenu'] = $items;
            
            $requestedUri = isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : false;
            
            if (!$requestedUri) 
            {
                $requestedUri = "/";
                //$Language = $config -> language_list[$config -> default_language]; //default
                //$page = $config -> default_model;
            }
        
            $url = $this -> parsing_url($requestedUri);

        }

        function parsing_url($ar_url)
        {
            global $db, $config;
            /* ----------------------------------------
            *    Parsing custom url
            *  ----------------------------------------
            */
            if (empty($ar_url) || count($ar_url) == 0) return false;
            
            //$config -> content['full_url'] = parse_url($ar_url); // !!!remove!!!
            
            $tPath = parse_url($ar_url);
            $this->requestedURL = $tPath['path'];
            
            //$config -> content['pathinfo'] = pathinfo($this->requestedURL);  // !!!remove!!!
            
            $this->pathInfo = pathinfo($this->requestedURL);
            $arUrl = $this->pathInfo;

            // check if file in (.css, .js, )
            if (isset($arUrl['extension']) && !empty($arUrl['extension']))
            {
                $includePaths = explode(PATH_SEPARATOR, ini_get("include_path"));
                //$includePaths = array_merge(array($arUrl['dirname']."/"), $includePaths);

                foreach($includePaths as $includePath)
                {
                    $bFoundFile = is_file(str_replace("//", "/", $includePath.$arUrl['dirname']."/".$arUrl['basename']));
                    if ($bFoundFile)
                    {
                        if (isset($config -> codepage) && ($config -> codepage != "")) 
                        {
                            $metaContentTypeCodePage = '; charset="' . $config -> codepage . '"';
                        } else {                                                                              
                            $metaContentTypeCodePage = '; charset="utf-8"';
                        }
                    
                        $commonIncludeFileFound = true;
                        $commonFileName = $this->requestedURL;
                        
                        if ($arUrl['extension'] == 'html') 
                        {
                            header("Content-type: text/html".$metaContentTypeCodePage);
                            require file_translate($commonFileName);
                
                        } elseif ($arUrl['extension'] == 'php') {
                            require $includePath.$commonFileName;
                        
                        } elseif ($arUrl['extension'] == 'css') {
                            //require_once "common/caching.html";
                            header("Content-type: text/css".$metaContentTypeCodePage);
                            require $includePath.$commonFileName;               
                            //require "compress-css.php";
                            
                        } elseif ($arUrl['extension'] == 'iaf') {
                            header("Content-Disposition: attachment; filename=$commonFileName");
                            header("Content-type: text/iaf".$metaContentTypeCodePage);
                            require $includePath.$commonFileName;
                            exit;
                            
                        } elseif ($arUrl['extension'] == 'txt') {
                            header("Content-type: text/plain".$metaContentTypeCodePage);
                            require file_translate($commonFileName);
                
                        } elseif ($arUrl['extension'] == 'htc') {
                            //require_once "common/caching.html";
                            header("Content-type: text/plain".$metaContentTypeCodePage);
                            require file_translate($commonFileName);
                            exit;
                
                        } elseif ($arUrl['extension'] == 'xml') {
                            header("Content-type: text/xml".$metaContentTypeCodePage);
                            require file_translate($commonFileName);
                
                        } elseif ($arUrl['extension'] == 'manifest') {
                            header("Content-type: text/cache-manifest");
                            require file_translate($commonFileName);
                
                        } elseif ($arUrl['extension'] == 'xsl') {
                            header("Content-type: text/xsl".$metaContentTypeCodePage);
                            require file_translate($commonFileName);
                
                        } elseif ($arUrl['extension'] == 'js') {
                            header("Content-type: application/x-javascript".$metaContentTypeCodePage);
                            readfile(file_translate($commonFileName), true);
                            //require file_translate($commonFileName);
                            //require "compress-js.php";
                
                        } elseif ($arUrl['extension'] == 'pdf') {
                            header("Content-type: application/pdf");
                            readfile($includePath.$commonFileName, true);
                
                        } elseif ($arUrl['extension'] == 'zip') {
                            header("Content-type: application/zip");
                            readfile($includePath.$commonFileName, true);
                            
                        } elseif ($arUrl['extension'] == 'wmv') {
                            header("Content-type: video/x-msvideo");
                            readfile($includePath.$commonFileName, true);
                
                        } elseif ($arUrl['extension'] == 'mp4') {
                            header("Content-type: video/mp4");
                            readfile($includePath.$commonFileName, true);
                
                        } elseif ($arUrl['extension'] == 'm4v') {
                            header("Content-type: video/mp4");
                            readfile($includePath.$commonFileName, true);
                
                        } elseif ($arUrl['extension'] == 'png') {
                            header("Content-type: image/png");
                            readfile($includePath.$commonFileName, true);
                
                        } else {
                            $commonImageType = getimagesize($includePath.$commonFileName);
                            if (
                                preg_match('/\/(images|common\/others)\//i', $includePath.$commonFileName)
                            )
                            {
                                //require_once "common/caching.html";
                
                            } elseif (preg_match('/\/ac\/editor\/images\//i', $includePath.$commonFileName)){
                                
                                header("Cache-Control: max-age=2592000");
                            }
                            
                            
                            if ($commonImageType !== false) 
                            {
                                header("Content-type: ".$commonImageType["mime"]);
                            }
                
                            readfile($includePath.$commonFileName, true);
                        }
                        
                
                        unset($metaContentTypeCodePage);
                        exit;
                        break;
                    }
                }   
            }
            if(isset($this->requestedURL)) 
            {
                $ar_url = $this->requestedURL;
                $ar_url = explode("/", trim($ar_url, "\/"));
            }
            
            if (!is_array($ar_url) || count($ar_url) == 0) return false;
    
            $ar_result = false;
            
            if (in_array($ar_url[0], $config -> language_list))
            {
                foreach($config->languages as $lang)
                {
                    if ($lang['lngUrl'] == $ar_url[0])
                    {
                        $ar_result["language"] = $lang;
                        $config -> set_current_language($lang['lngID']*1);
                        break;
                    }
                }
                unset($ar_url[0]);
            }else{
                $ar_result["language"] = $config->languages[$config->default_language];
            }
    
            $t_City = false;
            $t_Category = false;
            $t_Page = 'default';
            $this -> AdminView = false;
                        
            if(isset($ar_url[0]) && $ar_url[0] == "adminarea" && $this -> AdminView === false)
            {
                $this -> AdminView = true;
            }
                
            if($this -> AdminView)
            {
    
                $ar_result["controller"] = array(
                    "file"      => $config -> default_admin_controller . '.php',
                    "name"      => $config -> default_admin_controller,
                    "fullname"  => $config -> get_admin_controller_fullpath() . $config -> default_admin_controller . '.php', 
                );
                // set default model
                $ar_result["model"] = array(
                    "file"      => $config -> default_admin_model . '.php',
                    "name"      => $config -> default_admin_model,
                    "fullname"  => $config -> get_admin_model_fullpath() . $config -> default_admin_model . '.php', 
                );
                // set default template
                $ar_result["template"] = array(
                    "file"      => 'default.php',
                    "name"      => 'default',
                    "fullname"  => $config -> get_admin_template_fullpath().'default.php',
                );
            }else{
                // set default model
                $ar_result["model"] = array(
                    "file"      => $config -> default_model . '.php',
                    "name"      => $config -> default_model,
                    "fullname"  => $config -> get_model_fullpath() . $config -> default_model . '.php', 
                );
                // set default template
                $ar_result["template"] = array(
                    "file"      => 'default.php',
                    "name"      => 'default',
                    "fullname"  => $config -> get_template_fullpath().'default.php',
                );
            }

            $this->realURL = get_filename(implode('/', $ar_url));
            
            // check page template
            $path_parts = pathinfo(implode('/', $ar_url));
            // Get Content from DB 
            
            $this->setContent($this->realURL);
                        
    
            $t_filename = isset($path_parts['filename']) ? $path_parts['filename'] : false;
            
            //check model
            if ($this -> AdminView)
            {
                if (
                    fileexists(
                        $config -> get_admin_model_fullpath() . 
                        /*(isset($path_parts['dirname']) && $path_parts['dirname'] && $path_parts['dirname'] != "."  ? /$path_parts['dirname'] . '/' : '') .*/ 
                        $t_filename.'.php'
                    )
                )
                {
                    $ar_result["model"] = array(
                        "file" => $t_filename.'.php',
                        "name" => $t_filename,
                        "fullname" => $config -> get_admin_model_fullpath() . $t_filename.'.php',
                    );
                }
                
                //check template
                if (fileexists($config -> get_admin_template_fullpath() . $t_filename.'.php'))
                {
                    $ar_result["template"] = array(
                        "file" => $t_filename.'.php',
                        "name" => $t_filename,
                        "fullname" => $config -> get_admin_template_fullpath() . $t_filename.'.php',
                    );
                }
            }else{
                if (
                    fileexists(
                        $config -> get_model_fullpath() . 
                        (isset($path_parts['dirname']) && $path_parts['dirname'] && $path_parts['dirname'] != "."  ? /*"/".*/$path_parts['dirname'] . '/' : '') . 
                        $t_filename.'.php'
                    )
                )
                {
                    $ar_result["model"] = array(
                        "file" => $t_filename.'.php',
                        "name" => $t_filename,
                        "fullname" => $config -> get_model_fullpath() . (isset($path_parts['dirname']) && $path_parts['dirname'] && $path_parts['dirname'] != "."  ? /*"/".*/$path_parts['dirname'].'/' : '').$t_filename.'.php',
                    );
                }
                
                //check template
                if (fileexists($config -> get_template_fullpath().(isset($path_parts['dirname']) && $path_parts['dirname'] && $path_parts['dirname'] != "." ? $path_parts['dirname'].'/' : '').$t_filename.'.php'))
                {
                    $ar_result["template"] = array(
                        "file" => $t_filename.'.php',
                        "name" => $t_filename,
                        "fullname" => $config -> get_template_fullpath().(isset($path_parts['dirname']) && $path_parts['dirname'] && $path_parts['dirname'] != "." ? $path_parts['dirname'].'/' : '').$t_filename.'.php',
                    );
                }
            }
            return $this->check_routes($ar_result);
        }

        function check_routes($ar)
        {
            global $system_path;
            if (!is_array($ar)) return $ar;

            require ($system_path.'/route.php');
            
            if (isset($route) && is_array($route))
            {
                foreach($route as $r)
                {
                    if(!is_array($r)) continue;
                    $bId = true; 
                    $bParentId = true; 

                    if (isset($r['cntID']) && $r['cntID'] !== "*")
                    {
                        $bId = ($r['cntID']*1) == ($this->arPage['cntID']*1); 
                    }

                    if (isset($r['cntParentID']) && $r['cntParentID'] !== "*" && isset($this->arPage['cntParentID']))
                    {
                        $bParentId = ($r['cntParentID']*1) == ($this->arPage['cntParentID'] * 1); 
                    }
                    
                    if($bParentId && $bId)
                    {
                        $ar["model"] = (isset($r["model"]) && is_array($r["model"]) ? $r["model"] : $ar["model"]);
                        $ar["template"] = (isset($r["template"]) && is_array($r["template"]) ? $r["template"] : $ar["template"]);
                    }
                }

            }            
            return $ar;
        }
        
        function setContent($url = 'index') 
        {
            global $db, $config;
            $url = $url == "" ? "index" : $url;
            $sPath = preg_replace("/\.html$/", "", $url);
            
            $this->arFileNames  = explode("/", $sPath);
            $this->arIDs        = array();
            $this->arTitles     = array();
            $this->currentLevel = 0;
            
            $this->bWelcome = false;
            $this->bError   = false;
            
            $bFound = true;
            
            $items = $db->select("SELECT
                    cntID
            
                FROM
                    content
            
                WHERE
                    cntParentID = 0
                    AND
                    cntLanguageID = " . ($config -> get_current_language() * 1) . "
            ");
            
            $listParentID = $items[0]['cntID'];
            
            for ($i = 1; $i < count($items); $i++)
            {
                $listParentID .= ",".$items[$i]['cntID'];
            }
            
            for ($i = 0; $i < count($this->arFileNames); $i++)
            {
                $items = $db->select("SELECT
                        cntID, cntTitle,
                        cntIsURL, cntURL, cntParentID,
                        UNIX_TIMESTAMP(cntLastUpdate) AS setLastUpdate
            
                    FROM
                        content
                
                    WHERE
                        cntLanguageID = " . ($config -> get_current_language() * 1) . "
                        AND
                        cntParentID IN ($listParentID)
                        AND
                        cntFileName = '".addslashes($this->arFileNames[$i])."'
                ");
                
                if (count($items) == 0)
                {
                    $bFound = false;
                    break;
                }
                
                $listParentID = $items[0]['cntID'];
                array_push($this->arIDs, $items[0]['cntID']);
                array_push($this->arTitles, htmlspecialchars($items[0]['cntTitle']));
                $this->currentLevel++;
            }
            
            $this->bError = true;
            
            $this->bRedirect = false;
            $this->redirectURL = "";
            
            if ($bFound)
            {
                if ($items[0]["cntIsURL"] == 1)
                {
                    $this->bRedirect = true;
                    $this->redirectURL = $items[0]["cntURL"];
                }
                
                //setLastUpdate($items[0]['setLastUpdate']);
                $tPage = $this->getPage($items[0]['cntID']);
                $this->arPage = $tPage;
                $this->bError = false;

                /*if ($cntID == $this->_welcomeID)
                {
                    $this->bWelcome = true;
                } */
            }
        }
        
        // Get Page Content form DB
        function getPage($cntID) 
        {
            global $db, $config;
            
            $page = $db->select("SELECT
                    cntID, cntTitle, cntMETAKeywords, cntMETADescription, cntMetaTitle, cntBody,
                    cntChildContent, cntFileName, cntParentID, cntVisible
                
                FROM
                    content
                
                WHERE
                    cntID = $cntID
                    AND
                    cntLanguageID = " . ($config -> get_current_language() * 1) . "
            ");
        
            if (count($page) != 1) return false;
    
            $page = $page[0];
            $page["cntTitle"] = htmlspecialchars($page["cntTitle"]);
            
            //$page["cntBody"] = externallinks_Process(antispam_ProtectEmails($page["cntBody"]));
            
            return $page;
        }

        function getChildsPages($id = "")
        {
            global $config, $db;
            if ($id == "" || empty($id))
            {
                $id = isset($arPage["cntID"]) && $arPage["cntID"] > 0 ? $arPage["cntID"] : $config -> content['internal'];
            }
            
            $pages = $db->select("
                SELECT 
                    cntID, cntTitle, cntFileName, cntParentID 
                FROM 
                    content
                WHERE 
                    cntParentID = $id 
                    AND 
                    cntVisible = 1 
                    AND 
                    cntLanguageID = " . ($config -> get_current_language() * 1) . "
                ORDER BY
                    cntOrder, cntTitle"
            );
            
            if (count($pages) > 0) return $pages;
            
            return false;
        }
        
        //  function content Get Page URL
                 
        function getFullUrl($cntID, $bAbsolutePath = false, $bVisibleOnly = true) 
        {
            global $db, $config;
            
            $url = "";
            $id = $cntID;
            
            do {
                $item = $db->select("SELECT
                        cntID, cntParentID, cntIsGroup, cntFileName, cntIsURL, cntURL
                    
                    FROM
                        content
                    
                    WHERE
                        cntID = '$id'
                        AND 
                        cntLanguageID = " . ($config -> get_current_language() * 1) . "
                ");
                
                if (count($item) == 0) { $url = ""; break; } else { $item = $item[0]; }
                
                if ($item["cntIsGroup"] == '1') break;
                
                $url = $item["cntFileName"].($url == "" ? "" : "/$url");
                $id = $item["cntParentID"];
                
            } while ($id != '0');
            
            $url = ($bAbsolutePath ? "http://".$GLOBALS["HTTP_HOST"] : "")."$url";
            
            return $url;
        }

        // function hasChild
        
        function hasChild($iID) 
        {
            global $config, $db;
            
            $items = $db->select("SELECT
                    COUNT(*) AS counter
                
                FROM
                    content
                    
                WHERE
                    
                    cntParentID = '$iID'
                    AND
                    cntLanguageID = " . ($config -> get_current_language() * 1) . "
                    AND
                    cntVisible = 1
            ");
    
            return $items[0]["counter"] > 0;
        }
    
        function setWelcomePage()
        {
            $this->setContent();
        }
        
        function isWelcomePage() {
            return $this->bWelcome;
        }
        
        function getMainMenuID() {
            return $this->_mainMenuID;
        }
        
        function getFooterMenuID() {
            return $this->_footerMenuID;
        }
    }
    
    
 /*
    //  
    // Generate Menu Tree function
    //
    
    function generateMenuTree($iID, $hrefPrefix, $ignoreIDs = false, $_generatedMenuTree = false, $ulClass = "", $treeStyle = "full", $maxDeepLevel = 1000, $maxItemLength = 0) 
    {

        global $db, $content, $websiteLanguageID, $websiteProcessExternalLinks,
            $websiteReplaceFirstMenuItem;
        

        $treeID_List = "";
        $maxDeepLevel --;
        
        if (count($content->arIDs) == 0 && count($content->arFileNames) > 0)
        {
            $treeID_List = "0";
        }
        
        if ($treeStyle == "short")
        {
            for ($i = 0; $i < count($content->arIDs); $i++)
            {
                $treeID_List .= ($treeID_List > "" ? "," : "").$content->arIDs[$i];
            }
        }
        
        
        $websiteLanguage = isset($websiteLanguageID) ? $websiteLanguageID : 1;
        
        
        if (!$_generatedMenuTree)
        {
            $cache_id = md5($iID.'|'.$websiteLanguage.'|'.$hrefPrefix.'|'.($ignoreIDs ?  $ignoreIDs : 'false').'|'.implode('|', $content->arIDs));
            $data = cache_get($cache_id, 'func_generateMenuTree');
        
            if ($data[0])
            {
                print $data[1];
                return ;
            } 
        }
        
    
        $items = $db->select("SELECT
                ".(
                    ($websiteReplaceFirstMenuItem != "" && $content->_welcomeID > 0) ?
                        "IF(cntID = ".$content->_welcomeID.", '".addslashes($websiteReplaceFirstMenuItem)."', cntTitle) AS "
                        :
                        ""
                )."
                cntTitle, cntFileName, cntID, cntIsURL, cntURL
            
            FROM
                content
                
            WHERE
                cntParentID = '$iID'
                AND
                cntLanguageID = $websiteLanguage
                AND
                cntVisible = 1

                ".($ignoreIDs === false ? "" : "AND cntID NOT IN ($ignoreIDs)")."
            
            ORDER BY
                cntOrder
        ");

        
        $data = '';
        
        
        if (count($items) > 0)
        {
            $data .= "\n<UL".($ulClass != "" ? " class='$ulClass'" : "").">\n";


            for ($i = 0; $i < count($items); $i++)
            {
                if (in_array($items[$i]['cntID'], $content->arIDs)) {
                    $class = " class='selectedMenuItem' style='font-weight: bold;'";
                    
                    if ($i == count($items) - 1) {
                        $classLi = " class='selectedMenuItemLi latestMenuItemLi'";
                    } else {
                        $classLi = " class='selectedMenuItemLi'";
                    }
                    
                } else {
                    $class = "";
                    
                    if ($i == count($items) - 1) {
                        $classLi = " class='latestMenuItemLi'";
                    } else {
                        $classLi = "";
                    }
                    
                }
                

                if ($items[$i]['cntIsURL'])
                {
                    $link_href = $items[$i]['cntURL'];

                } else {
                    $link_href = $hrefPrefix.'/'.($items[$i]['cntFileName'] == "index" ? "" : $items[$i]['cntFileName'].($items[$i]['cntFileName'] != "" ? ".html" : ""));
                }

                
                $link_titl_html = safe_htmlspecialchars($items[$i]['cntTitle']);

                $link_titl = $maxItemLength ?
                    TrimText($link_titl_html, $maxItemLength)
                    :
                    $link_titl_html;
                
                
                if ($items[$i]['cntFileName'] == "index" && strlen($link_titl) > 50) {
                    $link_titl = "Home";
                }
                

                $data .= "\n<LI".$classLi."><A ".$class.' hRef="'.$link_href.'"><span'.(
                    $link_titl_html != $link_titl ? ' title="'.$link_titl_html.'"' : ''
                ).'>'.$link_titl."</span></A>\n";
                

                $item = $db->select("SELECT
                        cntID
                        
                    FROM
                        content
                        
                    WHERE
                        cntParentID = ".$items[$i]['cntID']."
                        AND
                        cntLanguageID = $websiteLanguage
                        AND
                        cntVisible = 1

                        ".($treeID_List != "" ? "AND cntParentID IN ($treeID_List)" : "")."
                ");
                

                if (
                    (count($item) > 0)
                    &&
                    ($maxDeepLevel > 0)
                ) {
                    $data .= generateMenuTree($items[$i]['cntID'], $hrefPrefix."/".$items[$i]['cntFileName'], $ignoreIDs, true, $ulClass, $treeStyle, $maxDeepLevel);
                }
                
                $data .= "</li>\n";
            }
    

            $data .= "\n</UL>\n";


            if ($websiteProcessExternalLinks) {
                $data = externallinks_Process($data);
            }
        }
        

        if (!$_generatedMenuTree) {
            cache_save($cache_id, $data, 'func_generateMenuTree');
            print $data;

        } else {
            return $data;
        }
    }
    
    // End function Generate Menu Tree
    
    
    //  
    // Generate Menu Tree function v2.1
    //
    
    function generateMenuTree2($iID, $hrefPrefix, $ignoreIDs = false, $_generatedMenuTree = false,
            $ulClass = "", $treeStyle = "full", $maxDeepLevel = 1000, $hintTitle = false, $iLimitAmountOfItems = -1, $tabs = "") 
    {

        global $db, $content, $websiteLanguageID, $websiteProcessExternalLinks,
            $websiteReplaceFirstMenuItem, $websiteContentCustomOrder, $websiteContentIncludeID;
        

        $treeID_List = "";
        $maxDeepLevel --;
        
        
        if (count($content->arIDs) == 0 && count($content->arFileNames) > 0) {
            $treeID_List = "0";
        }
        

        if ($treeStyle == "short") 
        {
            for ($i = 0; $i < count($content->arIDs); $i++) 
            {
                $treeID_List .= ($treeID_List > "" ? "," : "").$content->arIDs[$i];
            }
        }
        
        
        $websiteLanguage = isset($websiteLanguageID) ? $websiteLanguageID : 1;
        
        
        if (!$_generatedMenuTree) 
        {
            $cache_id = md5($iID.'|'.$websiteLanguage.'|'.$hrefPrefix.'|'.($ignoreIDs ?  $ignoreIDs : 'false').'|'.implode('|', $content->arIDs).'|'.$iLimitAmountOfItems);
            $data = cache_get($cache_id, 'func_generateMenuTree2');
        
            if ($data[0]) 
            {
                print $data[1];
                return ;
            } 
        }
        
    
        $items = $db->select("SELECT
                ".(
                    ($websiteReplaceFirstMenuItem != "" && $content->_welcomeID > 0) ?
                        "IF(cntID = ".$content->_welcomeID.", '".addslashes($websiteReplaceFirstMenuItem)."', cntTitle) AS "
                        :
                        ""
                )."
                cntTitle, cntFileName, cntID, cntIsURL, cntURL, cntMETADescription, cntNoFollow
            
            FROM
                content
                
            WHERE
                cntParentID = '$iID'
                AND
                cntLanguageID = $websiteLanguage
                AND
                cntVisible = 1
                ".($ignoreIDs === false ? "" : "AND cntID NOT IN ($ignoreIDs)")."
            
            ORDER BY
                ".($websiteContentCustomOrder ? $websiteContentCustomOrder : 'cntOrder')."

            ".($iLimitAmountOfItems != -1 ? " LIMIT $iLimitAmountOfItems " : "")."
        ");

        
        $data = '';
        
        
        if (count($items) > 0) 
        {
            $data .= ($tabs ? "\n$tabs" : '')."<ul".($ulClass != "" ? " class='$ulClass'" : "").">";


            for ($i = 0; $i < count($items); $i++) 
            {
                $externaltag = "";
                $class = '';

                if (!$tabs && !$i)
                {
                    $class .= ($class ? ' ' : '').'listFirst';
                }

                if (!$tabs && $i == count($items)-1)
                {
                    $class .= ($class ? ' ' : '').'listLast';
                }

                if (in_array($items[$i]['cntID'], $content->arIDs)) 
                {
                    if ($items[$i]['cntID'] == $content->arIDs[count($content->arIDs)-1]) 
                    {
                        $class .= ($class ? ' ' : '')."listNow";
                        $externaltag = "strong";
                    } else {
                        $class .= ($class ? ' ' : '')."listParent";
                        $externaltag = "em";
                    }
                }

                if ($items[$i]['cntIsURL']) {
                    $link_href = $items[$i]['cntURL'];

                } else {
                    $link_href = $hrefPrefix.'/'.($items[$i]['cntFileName'] == "index" ? "" : $items[$i]['cntFileName'].($items[$i]['cntFileName'] != "" ? ".html" : ""));
                }

                
                $link_titl = safe_htmlspecialchars($items[$i]['cntTitle']);
                
                
                if ($items[$i]['cntFileName'] == "index" && strlen($link_titl) > 50) {
                    $link_titl = "Home";
                }
                

                $item = $db->select("SELECT
                        cntID
                        
                    FROM
                        content
                        
                    WHERE
                        cntParentID = ".$items[$i]['cntID']."
                        AND
                        cntLanguageID = $websiteLanguage
                        AND
                        cntVisible = 1
                        ".($treeID_List != "" ? "AND cntParentID IN ($treeID_List)" : "")."
                ");
                
                
                $class = $class != '' ? " class='$class'" : '';

                $link_titl = WordsBreak($link_titl, 10);
                
                $data .= "\n\t$tabs<li$class".($websiteContentIncludeID ? ' id="cnt'.$items[$i]['cntID'].'"' : '')."><span>".($externaltag != '' ? "<$externaltag>" : "")."<a href=\"$link_href\"".
                    ($hintTitle === true && $items[$i]["cntMETADescription"] != '' ? ' title="'.$items[$i]["cntMETADescription"].'"' : '')
                    .($items[$i]["cntNoFollow"] == 1 ? ' rel="nofollow"' : '')
                    .">$link_titl</a>".($externaltag != '' ? "</$externaltag>" : "")."</span>";

                
                if (
                    (count($item) > 0)
                    &&
                    ($maxDeepLevel > 0)
                ) {
                    $data .= generateMenuTree2($items[$i]['cntID'], $hrefPrefix."/".$items[$i]['cntFileName'], $ignoreIDs, true, $ulClass, $treeStyle, $maxDeepLevel, $hintTitle, $iLimitAmountOfItems, "$tabs\t\t");
                }

                $data .= "</li>";
            }
    

            $data .= "\n$tabs</ul>\n".substr($tabs, 0, -1);


            if ($websiteProcessExternalLinks) {
                $data = externallinks_Process($data);
            }
        }
        

        if (!$_generatedMenuTree) {
            cache_save($cache_id, $data, 'func_generateMenuTree2');
            print $data;

        } else {
            return $data;
        }
    }
    
    // End function Generate Menu Tree v2

    //  
    // Generate Menu Site-Map Tree function
    //
    
    function generateMenuSiteMapTree($iID, $hrefPrefix, $ignoreIDs = false, $_generatedMenuTree = false,
            $ulClass = "", $treeStyle = "full", $maxDeepLevel = 1000, $hintTitle = false, $iLimitAmountOfItems = -1) 
    {


        global $db, $content, $websiteLanguageID, $websiteProcessExternalLinks,$websiteShopInstalled,
            $websiteReplaceFirstMenuItem, $websiteSiteMap2ShowCategories, $websiteSiteMap2ShowProducts,
            $ssCategory;
        

        $treeID_List = "";
        $maxDeepLevel --;
        
        
        if (count($content->arIDs) == 0 && count($content->arFileNames) > 0) {
            $treeID_List = "0";
        }
        

        if ($treeStyle == "short") {
            for ($i = 0; $i < count($content->arIDs); $i++) {
                $treeID_List .= ($treeID_List > "" ? "," : "").$content->arIDs[$i];
            }
        }
        
        
        $websiteLanguage = isset($websiteLanguageID) ? $websiteLanguageID : 1;
        
        
        if (!$_generatedMenuTree) 
        {
            $cache_id = md5($iID.'|'.$websiteLanguage.'|'.$hrefPrefix.'|'.($ignoreIDs ?  $ignoreIDs : 'false').'|'.implode('|', $content->arIDs).'|'.$iLimitAmountOfItems);
            $data = cache_get($cache_id, 'func_generateMenuSiteMapTree');
        
            if ($data[0]) {
                print $data[1];
                return ;
            } 
        }
        
    
        $items = $db->select("SELECT
                ".(
                    ($websiteReplaceFirstMenuItem != "" && $content->_welcomeID > 0) ?
                        "IF(cntID = ".$content->_welcomeID.", '".addslashes($websiteReplaceFirstMenuItem)."', cntTitle) AS "
                        :
                        ""
                )."
                cntTitle, cntFileName, cntID, cntIsURL, cntURL, cntMETADescription, cntNoFollow
            
            FROM
                content
                
            WHERE
                cntParentID = '$iID'
                AND
                cntLanguageID = $websiteLanguage
                AND
                cntVisible = 1
                ".($ignoreIDs === false ? "" : "AND cntID NOT IN ($ignoreIDs)")."
            
            ORDER BY
                cntOrder

            ".($iLimitAmountOfItems != -1 ? " LIMIT $iLimitAmountOfItems " : "")."
        ");

        
        $data = '';
        
        
        if (count($items) > 0) 
        {
            $data .= "\n<ul".($ulClass != "" ? " class='$ulClass'" : "").">";


            for ($i = 0; $i < count($items); $i++) 
            {
                $class = "";
                $externaltag = "";


                if (in_array($items[$i]['cntID'], $content->arIDs)) 
                {
                    if ($items[$i]['cntID'] == $content->arIDs[count($content->arIDs)-1]) 
                    {
                        $class = "listNow";
                        $externaltag = "strong";
                    } else {
                        $class = "listParent";
                        $externaltag = "em";
                    }
                }


                if ($i == count($items) - 1) 
                {
                    $class .= $class == '' ? '' : ' ';
                    //$class .= "listEnd";
                }
                

                if ($items[$i]['cntIsURL']) 
                {
                    $link_href = $items[$i]['cntURL'];

                } else {
                    $link_href = $hrefPrefix.'/'.($items[$i]['cntFileName'] == "index" ? "" : $items[$i]['cntFileName'].($items[$i]['cntFileName'] != "" ? ".html" : ""));
                }

                
                $link_titl = safe_htmlspecialchars($items[$i]['cntTitle']);
                
                
                if ($items[$i]['cntFileName'] == "index" && strlen($link_titl) > 50) 
                {
                    $link_titl = "Home";
                }
                

                $item = $db->select("SELECT
                        cntID
                        
                    FROM
                        content
                        
                    WHERE
                        cntParentID = ".$items[$i]['cntID']."
                        AND
                        cntLanguageID = $websiteLanguage
                        AND
                        cntVisible = 1
                        ".($treeID_List != "" ? "AND cntParentID IN ($treeID_List)" : "")."
                ");
                
                
                $class = $class != '' ? " class='$class'" : '';

                $link_titl = WordsBreak($link_titl, 10);
                
                $data .= "\n\t<li$class>\n\t\t<span>".($externaltag != '' ? "<$externaltag>" : "")."<a href=\"$link_href\"".
                    ($hintTitle === true && $items[$i]["cntMETADescription"] != '' ? ' title="'.$items[$i]["cntMETADescription"].'"' : '')
                    .($items[$i]["cntNoFollow"] == 1 ? ' rel="nofollow"' : '')
                    .">$link_titl</a>".($externaltag != '' ? "</$externaltag>" : "")."</span>";

                if ($items[$i]['cntID'] == 2)
                {
                    if ($websiteShopInstalled)
                    {
                        if ($websiteSiteMap2ShowCategories)
                        {
                            $categories = $ssCategory->getChildCategory();

                            if (count($categories) > 0)
                            {
                                $data.="<ul class=\"sitemapCatalog\">";
                            
                                foreach($categories as $categoryID)
                                {
                                    $data .= getTreeForCat($categoryID);

                                    if ($websiteSiteMap2ShowProducts)
                                    {
//                                      $data.="<li> List of Products</li>";
                                    }
                                }
    
                                $data.="</ul>";
                            }

                        }
                    
                    } // end $websiteSiteMap2ShowCategories
                
                } // end $websiteShopInstalled

                
                if (
                    (count($item) > 0)
                    &&
                    ($maxDeepLevel > 0)
                ) {
                    $data .= generateMenuTree2($items[$i]['cntID'], $hrefPrefix."/".$items[$i]['cntFileName'], $ignoreIDs, true, $ulClass, $treeStyle, $maxDeepLevel, $hintTitle);
                    //$data .= "\n";
                    $data .= "";
                }
                

                $data .= "\n\t</li>";
            }
    

            $data .= "\n</ul>";


            if ($websiteProcessExternalLinks) {
                $data = externallinks_Process($data);
            }
        }
        

        if (!$_generatedMenuTree) 
        {
            cache_save($cache_id, $data, 'func_generateMenuSiteMapTree');
            print $data;

        } else {
            return $data;
        }
    }
    
    function getTreeForCat($categoryID)
    {
        global $db, $ssCategory, $curCat, $REQUEST_URI;

        $category = $db -> select("
            SELECT catName FROM categories WHERE catID = ".$categoryID."
        ");
        
        // print link to this category

        $class = $class != '' ? " class='$class'" : '';
        $link_titl = WordsBreak($link_titl, 10);
        $listCategories = $ssCategory -> getChildArray("catID",$categoryID);
        $childCategories = $ssCategory -> getChildCategory($categoryID);

        if ($childCategories[0]*1 == -1) unset($childCategories);

        $data .= "
            <li>
                <span>".(count($childCategories) > 0 
                    ? 
                        '<a href="'.urlAddParam($REQUEST_URI, 'curCat', $categoryID*1).'#cat'.$categoryID.'"'.(in_array($curCat,$listCategories) ? ' name="cat'.$categoryID.'"' : '' ).'><img src="/images/common/'.(in_array($curCat,$listCategories) ? 'minus.gif" alt="Close" />' : 'plus.gif" alt="Open" />' )
                    : 
                        ""
                    ).
                    (count($childCategories) > 0 ? "</a>" : "")
                    ."<a href=\"".($ssCategory->getCategoryURL_byID($categoryID))."\">".$category[0]['catName']."</a>".(count($childCategories) > 0 && false ? "($categoryID)" : "")."</span>
        ".(count($childCategories) > 0 && $childCategories[0] > 0 && in_array($curCat,$listCategories) ? "<ul>" : "" );

//print("<pre>$categoryID");
//print_r($childCategories);
//print("</pre><br/>");
        if (count($childCategories) > 0  && $childCategories[0] > 0 && in_array($curCat,$listCategories))
        {
            foreach($childCategories as $childCatID)
            {
                if ($childCatID > 0)
                {
                    $data .= getTreeForCat($childCatID);
                }
            }
        }
        
        // get Products for this category
        // getProductURL($pdcID = -1, $catID = -1, $order_by = "")

        return $data. (count($childCategories) > 0 && $childCategories[0] > 0 && in_array($curCat,$listCategories) ? "</ul>" : "" )."</li>";
    }
                            
    
    // End function Generate Menu Site-Map Tree 
    
    
*/