<?php

/*
 * ----------------------------------------------------------------
 * Data for Calculator
 * ----------------------------------------------------------------
 * temporary! later from DB
 * */

    $arGame['name'] = 'Lineage';

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
                <p>«Линейка» -старая корейская, со своими плясами и минусами игра, которую можно назвать «фэнтези», так как присутствует колдовство, эльфы и гномы  переполняют игровое пространство Еще ее можно назвать «гринд» - уничтожение монстров  - одно из главных задач игры, и «ганк» - к вам постоянно будут наведываться непрошенные гости, от которых нужно будет избавлятьсяч. За 10 лет своего существования игра претерпела определенные изменения, но так и осталась одной из популярных, а ее верные сторонники с восторгом восприняли появление обновленной версии.</p>
                <p>Любителей Lineage 2 Classic восхищают жесточайшие сражения за замки, героические походы против полчищ монстров, войны между союзами и кланами.</p>
                <p>Особенности Lineage 2 Classic:</p>
                <ul>
                    <li>Способов заработать стало значительно больше, благодаря, хоть и сложной, но рациональной системе развития экономики.</li>
                    <li>Существует 5 рас и 31 класс. Чтобы иметь успех в войне пати-групп, нужно взаимодействие всех классов со своими неповторимыми способностями.</li>
                    <li>Все уровни насыщены уникальными игровыми событиями.</li>
                    <li>За счет восстановления опыта стало проще достигать высоких уровней.</li>
                </ul>
                <p>Сервис  ECM-GEMES поможет вам в продвижении персонажа, так как предоставляет услугу «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в реальные деньги.<p>

                <p>Создавайте свой клан, добывайте артефакты, не позволяйте это делать противнику, ваша задача не просто выжить, а заставить всех считаться с вами!</p>
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

