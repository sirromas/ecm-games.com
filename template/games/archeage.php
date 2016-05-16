<?php

/*
 * ----------------------------------------------------------------
 * Data for Calculator
 * ----------------------------------------------------------------
 * temporary! later from DB
 * */

$arGame['name'] = 'ArcheAge';

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
                <p>Безграничные территории, в которых вы – правитель, властелин, воин  и хозяин.  Можешь
                    сражаться  с врагами, убивать монстров,  добывать ресурсы,  выращивать урожай,  торговать,
                    перевозить грузы, строить дома, корабли даже замки. Вам придется быть хорошим экономистом.
                    Сначала складывается впечатления, что все легко и просто: уровень летит за уровнем, не успел
                    оглянуться, а ты уже кап. Но не обольщайтесь, именно теперь все начинается по-настоящему, ведь
                    вам потребуется супер-экипировка и без путешествий и приключений не обойтись.</p>
                <p>В ArcheAge существует четыре расы: две расы людей, зверолюди, эльфы. Классы (специальности)
                    выбираются игроком по усмотрению, но одновременно не больше 3-ех из 10-ти. Вариантов много:
                    соединяйте несоединимое,  на все ваша воля. Графика базируется на  Cri Engine 3, и хоть
                    используется мультипликация, но зато колоритная, впечатляющая, насыщенная эффектами.</p>
                <p>Сервис  ECM-GAMES поможет вам в продвижении персонажа, так как предоставляет услугу
                    «покупка-продажа игровых валют». Также вы имеете возможность из участника превратиться в
                    партнера: накопившийся у вас излишек игровой валюты или ресурсов, можно превратить в
                    реальные деньги.</p>
                <p>Хотите острых ощущений, «Arche Age» открыт для всех, кого манят опасности и далекие края!</p>
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