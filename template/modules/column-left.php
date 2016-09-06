        <!-- Левый блок -->
        <div id="sidebar-left">
            <div id="block-category">
                <div class="title-box"><h2>Выбор игры</h2></div>
                <ul>
<?php 
    $gamesPages = $content -> getChildsPages(201);
    if ($gamesPages) 
    {
        $countPunkt = count($gamesPages);
        foreach($gamesPages as $i => $punkt)
        {
            print("
                    <li><a href=\"".getNewURL("-", $content->getFullUrl($punkt['cntID']))."\" title=\"" . htmlspecialchars($punkt['cntTitle']) ."\">" . htmlspecialchars($punkt['cntTitle']) ."</a></li>");
        }
        /*<a href=\"".getNewURL("-", $punkt['cntFileName'])."\" title=\"" . htmlspecialchars($punkt['cntTitle']) ."\">".$punkt['cntTitle']."</a>"*/
    }
?>
                    

                </ul>
            </div>
        </div>
        <!-- Левый блок /-->
