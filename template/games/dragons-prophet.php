<?php

    /*
     * ----------------------------------------------------------------
     * Data for Calculator
     * ----------------------------------------------------------------
     * temporary! later from DB
     * */

    $arGame['name'] = 'Dragon’s Prophet';

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
<p>Игра в русской версии появилась совсем недавно. Все пространство наводнено драконами, и самыми различными: один виртуозно летает, второй превосходно ныряет, третий лучше всех бегает. И пусть они все необычны, главное, что все приручаемы. Пройдите несложный квест, и дракон ваш и не один. Захотите жизни посложнее, можете приручить легендарного дракона. Рептилия не просто экзотическое транспортное средство, но и боевой друг.</p>
<p>С повышением уровня, у дракона повышаются и способности. Его образ зависит от ситуации и выбранного вами класса. Существуют три основных класса: Страж (меч), Следопыт (лук), Теург (стихии).  Четвертый – Оракул, который легко ладит с драконами и сам, как воин, грозен и силен.</p>
<p>Всем понравится  Non-target – эффектная система боя, как и у TERA. А система PvP боев выглядит ярко, интересно, динамично. Кстати, вам придется не только воевать, но найдется место и для мирной жизни. Поупражняйтесь в кузнечном деле или алхимии. Вам нужно будет производить уйму невероятных предметов. Когда же ваше богатство достигнет высокого уровня, то доведется подумать о постройке собственного дома.</p>
<p>Сервис  ECM-GAMES поможет вам в продвижении персонажа, так как предоставляет услугу «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в реальные деньги.</p>
<p>Создайте свой индивидуальный образ, оседлайте легендарного дракона и наслаждайтесь невероятными сражениями, которыми переполнен мир Dragon’s Prophet.</p>
                </div>  

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