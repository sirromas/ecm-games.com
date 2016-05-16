<?php

    /*
     * ----------------------------------------------------------------
     * Data for Calculator
     * ----------------------------------------------------------------
     * temporary! later from DB
     * */

    $arGame['name'] = 'Path of Exile';

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
<p>Игра разработана компанией Grinding Gear Games (Новая Зеландия) Действие происходит в безжалостном и бескомпромиссном мире Wraeclast. По своему стилю – это темное фэнтези. Пространство разделено на, так называемые, инстансы. Группировки игроков, лучше всего 4-8 бойцов, получают копию такой области. Бои могут происходить и между отдельными группами, и между командами (альянсами).</p>
<p>Существуют и хабы (общедоступные области), которые служат местом торговли и поиска игроков для группировки. Раскрутить персонаж можно, используя игровые деньги – перки. С их помощью игрок меняет внешний вид, анимацию, например, можно сделать дополнительные жесты, скины для различных предметов, «декоративных» животных.</p>
<p>В игре существует 6  классов. Генерация локаций происходит случайно, что делает игру непредсказуемой. Большой интерес у поклонников игры Path of Exile вызывают турниры за мировое признание для команд с разным  числом бойцов.</p>
<p>Сервис  ECM-GAMES поможет вам в продвижении персонажа, так как предоставляет услугу «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в реальные деньги.</p>
<p>Если у вас бесстрашное сердце, то до встречи в аду, мире Wraeclast!</p>
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