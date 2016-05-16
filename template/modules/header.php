    <header>
        <div class="logo">
            <a href="/" title="<%%>Home page<%%>"></a>
        </div>
        <div class="cont-form">
            <div class="reg none">
                <a href="" title=""><%%>Signup<%%></a>
            </div>
            <div class="login none">
                <a class="buttonVhod" href="" title=""><%%>Signin<%%></a>
            </div>  
            <div id="flags">
                <a class="en" href="/en" title="English"></a>
                <a class="ru" href="/ru" title="Русский"></a>
                <a class="ua" href="/ua" title="Українська"></a>
            </div>      
        </div>
        <div id="menu-icon">
            <div id="icon-menu"></div>
            <div id="icon-category"></div>
        </div>  
        <div id="menu">
            <ul>
<?php
    if ($content->arMainMenu && is_array($content->arMainMenu))
    {        
        foreach($content->arMainMenu as $item)
        {
?>                <li><a href="<?= getNewURL("-", $item['cntFileName']) ?>" title="<?= $item['cntTitle'] ?>"><?= $item['cntTitle'] ?></a></li>
<?php            
        }
    }
?>
<?php 
    $footerMenuPages = $content -> getChildsPages($config->content['footer']);
    if ($footerMenuPages) 
    {
        $countPunkt = count($footerMenuPages);
?>
            <div id="footermenu">
<?php
        foreach($footerMenuPages as $i => $punkt)
        {
            print("<li><a href=\"".getNewURL("-", $punkt['cntFileName'])."\" title=\"" . htmlspecialchars($punkt['cntTitle']) ."\">".$punkt['cntTitle']."</a></li>");
        }
?>
            </div>
<?php 
    }
?>
            </ul>
        </div>
    </header>
