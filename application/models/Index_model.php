<?php

class index_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_index_page() {
        $list = "";
        $list.="<h1>Магазин игровой валюты</h1>
                <div class='category-img'>
                <nav>
                <a href='/ru/games/aion' title='AION'><img src='/games/assets/icon/aion.jpg' title='AION' alt='AION'/></a>
                <a href='/ru/games/starwars-old-republic' title='Star Wars: The Old Republic'><img src='/games/assets/icon/starwars-old-republic.jpg' title='Star Wars: The Old Republic' alt='Star Wars: The Old Republic'/></a>
                <a href='/ru/games/terra-online' title='TERA Online'><img src='/games/assets/icon/terra-online.jpg' title='TERA Online' alt='TERA Online'/></a>
                <a href='/ru/games/lineage' title='Lineage'><img src='/games/assets/icon/lineage.jpg' title='Lineage' alt='Lineage'/></a>
                <a href='/ru/games/dragon-nest' title='Dragon Nest'><img src='/games/assets/icon/dragon-nest.jpg' title='Dragon Nest' alt='Dragon Nest'/></a>
                <a href='/ru/games/royal-quest' title='Royal Quest'><img src='/games/assets/icon/royal-quest.jpg' title='Royal Quest' alt='Royal Quest'/></a>
                <a href='/ru/games/neverwinter' title='Neverwinter'><img src='/games/assets/icon/neverwinter.jpg' title='Neverwinter' alt='Neverwinter'/></a>
                <a href='/ru/games/wildstar' title='Wildstar'><img src='/games/assets/icon/wildstar.jpg' title='Wildstar' alt='Wildstar'/></a>
                <a href='/ru/games/the-elder-scroll-online' title='The Elder Scrolls Online'><img src='/games/assets/icon/the-elder-scroll-online.jpg' title='The Elder Scrolls Online' alt='The Elder Scrolls Online'/></a>
                <a href='/ru/games/blade-and-soul' title='Blade &amp; Soul'><img src='/games/assets/icon/blade-and-soul.jpg' title='Blade &amp; Soul' alt='Blade &amp; Soul'/></a>
                <a href='/ru/games/eve-online' title='EVE Online'><img src='/games/assets/icon/eve-online.jpg' title='EVE Online' alt='EVE Online'/></a>
                <a href='/ru/games/allods-online' title='Аллоды Online'><img src='/games/assets/icon/allods-online.jpg' title='Аллоды Online' alt='Аллоды Online'/></a>
                <a href='/ru/games/karos-online' title='Karos Online'><img src='/games/assets/icon/karos-online.jpg' title='Karos Online' alt='Karos Online'/></a>
                <a href='/ru/games/world-of-warcraft' title='World Of Warcraft'><img src='/games/assets/icon/world-of-warcraft.jpg' title='World Of Warcraft' alt='World Of Warcraft'/></a>
                <a href='/ru/games/perfect-world' title='Perfect World'><img src='/games/assets/icon/perfect-world.jpg' title='Perfect World' alt='Perfect World'/></a>
                <a href='/ru/games/path-of-exile' title='Path of Exile'><img src='/games/assets/icon/path-of-exile.jpg' title='Path of Exile' alt='Path of Exile'/></a>
                <a href='/ru/games/dragons-prophet' title='Dragons Prophet'><img src='/games/assets/icon/dragons-prophet.jpg' title='Dragons Prophet' alt='Dragons Prophet'/></a>
                <a href='/ru/games/archeage' title='ArcheAge'><img src='/games/assets/icon/archeage.jpg' title='ArcheAge' alt='ArcheAge'/></a>            
                </nav>
                </div>
                <div>
                </div>";
        return $list;
    }

}
