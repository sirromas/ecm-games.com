<?php

    /*
     * ----------------------------------------------------------------
     * Data for Calculator
     * ----------------------------------------------------------------
     * temporary! later from DB
     * */

    $arGame['name'] = 'Star Wars: The Old Republic';

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
<p>Действие игры происходит за 3 500 лет до событий легендарной эпопеи Д.Лукаса. Тысячелетия  галактическая Республика, находясь под защитой Ордена Джедаев, несла мир и благополучие цивилизации. Однако, темная Империя  Ситхов, окрепнув и собрав силы, начала жесточайшую войну против Джедаев и захватила планету Корускант. Подписано шаткое перемирие. Война назревает, вопрос только, когда она начнется. Новое поколение жаждет крови, оно не может жить в неопределенности, мир будет принадлежать сильнейшему.</p>
<p>Самый большой недостаток – длинные диалоги, подробные инструкции, от которых никуда не деться. Приступая к игре, выберите сторону: Республику или Империю. Затем определитесь с классом персонажа. У каждой стороны есть одинаковые классы: домагер, разбойник, воин и маг, только называются они по-разному. В игре существует 9 рас, но сначала доступ к ним ограничен, чтобы открыть доступ, нужно выполнять задания или купить за деньги.</p>
<p>Будьте готовы, что вначале квесты будут  однотипными, но уже с 10-го уровня начнется настоящая бойня. Проверить свои навыки можно в специальных зонах. Также можно совершать космические полеты, круша все, что попадается на пути. Правда, с динамикой игры есть проблемы: энергичным геймерам это быстро наскучит. Анимация классическая в стиле звездных войн, а вот звуковые эффекты и музыка на высоте. Однако, есть проблема для русскоязычных пользователей: игра не переведена на русский язык. Но поклонникам звездной эпопеи Star Wars: The Old Republic придется по вкусу.</p>
<p>Сервис  ECM-GAMES поможет вам в продвижении персонажа, так как предоставляет услугу «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в реальные деньги.</p>
<p>Станешь ли ты последним избранником Ордена Джедаев или примешь темную сторону, решать тебе. В любом случае судьба галактики в твоих руках!</p>
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
