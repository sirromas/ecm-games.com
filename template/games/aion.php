<?php

    /*
     * ----------------------------------------------------------------
     * Data for Calculator
     * ----------------------------------------------------------------
     * temporary! later from DB
     * */

    $arGame['name'] = 'AION';

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
<p>AION – ролевая игра, разработанная компанией  NCSoft (Корея). С самого начала игры вас окружает мир светлых и темных сил, правда, есть третья сила – Балауры – NPS-раса, тоже борющаяся за место во Вселенной. Графика миров соответствует их образу мышления и принципам жизни: светлый – радость, яркость и оптимизм, темный – мрак, запустение и угнетенность.</p>
<p>Первоначально бог Айон создал идеальную Вселенную, в которой люди жили в гармонии, но тут вмешались драконы (балауры)  и разрушили мир и покой. Чтобы уровнять силы Айон дал людям крылья и они смогли взлететь в небо. После этого было заключено перемирие. Вы можете управлять либо цветущим миром  элийцев, либо опустошенным миром  асмодианцев.  Балауры же подконтрольны разработчикам. Класс (маг, жрец, воин, следопыт) вы выбираете сами.</p>
<p>Квесты динамичны, чаще всего нужно преодолевать большие расстояния, чтобы достичь определенной цели. С 25 уровня начинаются PvP бои. Возможно, задания и затянуты, но, наслаждаясь качественной графикой, так увлекаешься, что об этом забываешь. А чего только стоит полет над локацией? Ощущение, что ты – сверхчеловек! Это, поверьте, стоит попробовать!</p>
<p>Большая роль в достижении цели предназначена игровой валюте – Кинарам. С их помощью даже новичок, прокачав  свой персонаж, запросто может потаскаться с матерым игроком.</p>
<p>Сервис  ECM-GEMES поможет вам в продвижении персонажа, так как предоставляет услугу «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в реальные деньги.</p>
<p>Вселенная ждет нового воина, который изменит баланс сил в параллельном пространстве в пользу добра и света.  Возможно, этим  сверхбойцом станете именно Вы?! </p>
                </div>  
                <div id="video"><iframe width="560" height="315" src="https://www.youtube.com/embed/5PAM0wr7cZ8" frameborder="0" allowfullscreen></iframe></div>
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