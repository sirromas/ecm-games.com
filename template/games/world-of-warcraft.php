<?php

    /*
     * ----------------------------------------------------------------
     * Data for Calculator
     * ----------------------------------------------------------------
     * temporary! later from DB
     * */

    $arGame['name'] = 'World Of Warcraft';

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
<p>World of Warcraft  — ролевая онлайн-игра, авторами которой является творческий коллектив компании Blizzard Entertainment. В одном из пунктов книги Гиннеса записано, что игра собрала самое большое количество участников – двенадцать миллионов!!! Это о многом говорит!</p>
<p>В онлайне вы можете управлять своим персонажем, исследуя местность, уничтожая чудовищ, а еще выполнять небольшие задания. Но еще вас ожидают сражения с другими персонажами: дуэли на аренах разных размеров, на оборудованных полях или бои против игроков другой группировки. За квесты положены бонусы: деньги, предметы, опыт; за поединки – «очки чести», которые обычно вкладывают в опыт или экипировку. В игре существует 13 рас и 11 классов. Все расы разделены на две противоборствующие силы: Добра (Альянс) и Зла (Орда).</p>
<p>Пространство WoW – невообразимо причудливо: здесь можно увидеть затонувшие корабли, опустошенные селения, таинственные казематы и тьму монстров разных мастей. Цели игры определяете сами для себя: вы можете стать исследователем, мастером, гладиатором, даже, достигнув наивысшего уровня, интерес к игре не угасает, так как разработчики «подкидывают пробблемы» игрокам высокого уровня.</p>
<p>Сервис  ECM-GAMES поможет вам в продвижении персонажа, так как предоставляет услугу «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в реальные деньги.</p>
<p>Кем бы вы ни были: монахом, чернокнижником, друидом, жрецом, шаманом у всех одна цель – постичь тайны Мира Военного Искусства и взойти на наивысшую  ступень воинской иерархии.</p>
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
