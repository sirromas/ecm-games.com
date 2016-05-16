<?php
/**
 * Created by PhpStorm.
 * User: Andrii
 */
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
            <h1>Магазин игровой валюты</h1>
            <p>Благодарим Вас за сделаный заказ!</p>
            <p>В близжайшее время с Вами свяжется оператор, по указанным при оформлении контактам, для согласования сделки.</p>
            <p>Спасибо за доверие к нашему сервису!</p>
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