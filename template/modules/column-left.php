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
                    <!--li><a href="/games/terra-online" title="Tera Online">Tera Online</a></li>
                    <li><a href="/games/archeage" title="ArcheAge">ArcheAge</a></li>
                    <li><a href="/games/liniage" title="Lineage">Lineage</a></li>
                    <li><a href="/games/aion" title="AION">AION</a></li>
                    <li><a href="/games/dragons-prophet" title="Dragon’s Prophet">Dragon’s Prophet</a></li>
                    <li><a href="/games/path-of-exile" title="Path of Exile">Path of Exile</a></li>
                    <li><a href="/games/perfect-world" title="Perfect World">Perfect World</a></li>
                    <li><a href="/games/world-of-warcraft" title="World of Warcraft">World of Warcraft</a></li>
                    <li><a href="/games/karos-online" title="Karos Online">Karos Online</a></li>
                    <li><a href="/games/allods-online" title="Аллоды Онлайн">Аллоды Онлайн</a></li>
                    <li><a href="/games/eve-online" title="EVE Online">EVE Online</a></li>
                    <li><a href="/games/blade-and-soul" title="Blade And Soul">Blade And Soul</a></li>
                    <li><a href="/games/starwars-old-republic" title="Star Wars: The Old Republic">Star Wars: The Old Republic</a></li>
                    <li><a href="/games/the-elder-scroll-online" title="The Elder Scrolls Online">The Elder Scrolls Online</a></li>
                    <li><a href="/games/wildstar" title="WildStar">WildStar</a></li>
                    <li><a href="/games/neverwinter" title="Neverwinter">Neverwinter</a></li>
                    <li><a href="/games/royal-quest" title="Royal Quest">Royal Quest</a></li>
                    <li><a href="/games/dragon-nest" title="Dragon Nest">Dragon Nest</a></li-->

                </ul>
            </div>
        </div>
        <!-- Левый блок /-->
