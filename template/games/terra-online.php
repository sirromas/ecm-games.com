<?php

/*
 * ----------------------------------------------------------------
 * Data for Calculator
 * ----------------------------------------------------------------
 * temporary! later from DB
 * */

$arGame['name'] = 'TERA Online';

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
                <p>TERA – фантастическая стратегия MMO RPG новейшего времени,  в которую влюблены сотни тысяч
                    геймеров.  Игра получила множество международных наград, что свидетельствует о ее
                    привлекательности и востребованности. «За высочайшую и оригинальную графику», «за лучшую
                    F2p MMO», «за уникальную боевую систему» - это далеко неполный  перечень  достоинств TERA ,
                    признанных международными экспертами компьютерных игр.</p>
                <p>Не хочешь скуки и однообразия, любишь высококачественную графику, тогда огромный
                    фантастический мир TERA предстанет перед вами во всей своей первозданной красоте. «Non-
                    target» - уникальнейшая боевая система обеспечит вам адреналиновую бурю эмоций. Например,
                    эпическую битву с монстрами выиграет не амбициозный  хвастун, а  сноровистый и продуманный
                    полководец. Может быть, им станете вы, и именно вас ожидают лавры триумфатора?!</p>
                <p>Сервис  ECM-GAMES поможет вам в продвижении персонажа, так как предоставляет услугу
                    «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в
                    партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в
                    реальные деньги.</p>
                <p>Итак, в бой, искатели приключений и открыватели Tera incognita!</p>
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
