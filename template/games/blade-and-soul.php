<?php

    /*
     * ----------------------------------------------------------------
     * Data for Calculator
     * ----------------------------------------------------------------
     * temporary! later from DB
     * */

    $arGame['name'] = 'Blade & Soul';

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
<p>Игра разработана NCsoft.  Ее девиз можно определить так: «Экшн и только экшн!» Вселенная насквозь пропитана корейской мифологией. Взглянув на скрины и систему боя, можно смело сказать, что это оригинальное явление в онлайн-играх. К вашим услугам мечи, топоры, молоты, ну и как же без магии. Однако, вам предстоит не только махать оружием, ставить блоки и наносить удары врагу, но очень многие квесты требуют слаженных действий команды бойцов.</p>
<p>Расы мира Blade & Soul
    <ul>
        <li>Gon -  раса, которая появилась из пламени дракона. Сильные, энергичные, отличные бойцы.</li>
        <li>Jin – джины, находящиеся в гармонии с собой и с миром. Все предвидят, имеют высокую интуицию. Хладнокровные воины.</li>
        <li>Kun – прекрасные и изысканные женщины. Хоть и не являются воинами, но за миленькой внешностью кроется гибель непредусмотрительному бойцу.</li>
        <li>Lyn –небольшие ростом, с ушками, хвостиками и животным желанием выжить любой ценой и утвердиться в среде гуманоидных бойцов.</li>
    </ul>
</p>
<p>Blade Master – мастер клинка,  Kund-Fu Master – противостоит даже самому страшному оружию голыми руками,  Destroye – мастер двуручного оружия,   Force Master – мастер мысли. Для прокачки персонажа существует внутриигровая валюта – золото.</p>
<p>Сервис  ECM-GAMES поможет вам в продвижении персонажа, так как предоставляет услугу «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в реальные деньги.</p>
<p>Мир Blade & Soul был прекрасен, пока демонические силы не разрушили его. Сами небеса вступились за добро и справедливость, призвав Великих мастеров на помощь. Стань одним из них, тебя ждут великие битвы и великие подвиги!</p>
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