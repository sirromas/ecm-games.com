<?php

    /*
     * ----------------------------------------------------------------
     * Data for Calculator
     * ----------------------------------------------------------------
     * temporary! later from DB
     * */

    $arGame['name'] = 'Perfect World';

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
<p>Perfect World – (идеальный мир) – ролевая онлайн-игра, созданная в Китае. Вас ожидает мир китайской мифологии: красочная и многоплановая панорама Вселенной праотца Паньгу, где не существуют гравитационные законы и летать по воздуху на мечах или гулять под водой – явление нормальное.</p>
<p>Играя в Perfect World, вы получаете некоторые удивительные возможности: магию 5-ти элементов, различное оружие и амуницию, зелья и порошки. Создавая своего персонажа с помощью супер-редактора внешности, который регулирует каждую деталь лица, вы можете сделать его неповторимо уникальным. В игре существует 3 расы: сиды (крылатые эльфы), люди и зооморфы, и каждая обладает своими уникальными качествами.</p>
<p>Проходя 1 уровень, игроку начисляется 5 баллов: они используются по вашему усмотрению на повышение характеристик и экипировки персонажа. С 5-го уровня вам предоставляется возможность развить мастерство кузнеца или портного, аптекаря или ремесленника и создавать свое оружие, одежду, целебные отвары или украшения. Игровая валюта выбрана без мудрствования – юань. Выделиться в игре можно при помощи неординарной внешности, одежды и бижутерии. Приобретаются  уникальные предметы в Лавке Радостей и не юани, а за голды.</p>
<p>Сервис  ECM-GAMES поможет вам в продвижении персонажа, так как предоставляет услугу «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в реальные деньги.</p>
<p>Если вы – фанат китайской культуры, мифологии, с чистой душой и крепкой рукой, вдумчивы и расчетливы – ваше место в строю защитников Идеального Мира.</p>
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