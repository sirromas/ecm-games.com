<?php

    /*
     * ----------------------------------------------------------------
     * Data for Calculator
     * ----------------------------------------------------------------
     * temporary! later from DB
     * */

    $arGame['name'] = 'Neverwinter';

    require_once("template/modules/loaddatagame.php");

    require_once("template/modules/doctype.php");
?>
<html>
<?php
    require_once file_translate("template/modules/head.php");
?>
<body>

    <div id="container">
<?php
    require_once file_translate("template/modules/header.php");
?>
        <div id="content">
<?php
    require_once file_translate("template/modules/column-left.php");
?>
            <!-- Контент -->
            <div id="main">
                <h1><?= $arGame['name']?></h1>
<?php
    require_once file_translate("template/modules/calculator.php");
?>
                <div id="description">
<p>111Так называется прекрасный город, находящийся на Севере, омываемый водами волшебной реки, поэтому никогда не замерзает.  Именно в нем и в его окрестностях развиваются события игры. Разработчики сделали поединки максимально динамичными и, к тому же, упростили игру. По сути, она – сессионная и крутится вокруг PvP-арен (конечно, на любителя), одиночных квестов и подземелий.</p>
<p>В игре есть такие рассы: человек, полуорк, дворф, полурослик, тифлинг (получеловек, полудемон), эльф, полуэльф. Классы: Плут-ловкач, Бесстрашный воин (гладиатор, берсерк с двуручным оружием), Воин-страж (танк), Маг, Охотник-следопыт (лучник), Клирик (целитель). Даже, если вы достигли максимального 60-го уровня, вам есть чем заняться: разработчики побеспокоились о том, чтобы вы не потеряли интерес к игре.</p>
<p>Фишкой Neverwinter является мастерская. В ней игроки создают квесты друг для друга: головоломки, экшны. А самое главное – в мастерской вы можете создавать свои подземелья и выставлять напоказ.  В случае, если людям понравится ваше подземелье, то вас ожидает бонус – астральные бриллианты. </p>
<p>Вдобавок, игра бесплатная и русифицированная.</p>
<p>Сервис  ECM-GAMES поможет вам в продвижении персонажа, так как предоставляет услугу «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в реальные деньги.</p>
<p>Игра очень привлекательна и эффектная, как же без вас?! Защитите Neverwinter от нашествия врагов! Добудьте славу, признание и богатство!</p>
                </div>  
                <div id="video"><iframe width="560" height="315" src="https://www.youtube.com/embed/zMRvrUZL5yk" frameborder="0" allowfullscreen></iframe></div>
            </div>
        <!-- Контент /-->
<?php
    require_once file_translate("template/modules/column-right.php");
?>
        </div>

    </div>  
<?php
    require_once file_translate("template/modules/footer.php");
?>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            $("#icon-menu").on("click", function(){
                $(this).toggleClass("active");
                $("#menu ul").toggleClass("toggled");
            });
            $("#icon-category").on("click", function(){
                $(this).toggleClass("active");
                $("#block-category").toggleClass("toggled");
            });
        });
    </script>
</body>