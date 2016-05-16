<?php

/*
 * ----------------------------------------------------------------
 * Data for Calculator
 * ----------------------------------------------------------------
 * temporary! later from DB
 * */

    $arGame['name'] = 'Karos Online';

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
<p>Азмар – мир, который непобедимая злая сущность держит в постоянном страхе и ужасе. Расы, присутствующие в игре, не являются соперниками между собой: они все борются против одного врага – Зла. В игре существует 4 расы со своими уникальными способностями. Особое место занимает графика: детали проработаны до такой степени, что четко видны мельчайшие элементы одежды, в том числе заклепки, застежки, пуговицы.</p>
<p>Karos Online – это сплошные битвы, не только с использованием холодного оружия, но и очень «горячего» - фаерболлов.  А еще у вас есть возможность раскрутить свой персонаж, используя игровую валюту – караты. Также можно собирать магическую силу – ману (флетту), что тоже способствует прокачке героя. Если вы пришли в игру всерьез и надолго, то  начинающему игроку есть смысл вложить некоторую сумму на раскрутку персонажа. Без каратов играть в Karos Online крайне сложно: за них покупается оружие, амуниция, магические снадобья. Кроме этого, за почту и хранение предметов тоже нужно платить. К примеру, есть одно супер-умение – «крик тени» против лобов, - оно стоит целых 50 карат за разовое использование.</p>
<p>Сервис  ECM-GAMES поможет вам в продвижении персонажа, так как предоставляет услугу «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в реальные деньги.</p>
<p>В мире Азмар силы приблизительно равны, каждый воин на счету. Кто нарушит баланс? Кто вобьет последний гвоздь в гроб злой сущности? Прокачай свой персонаж и стань супер-героем!</p>
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