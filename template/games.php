<?php

    /*
     * ----------------------------------------------------------------
     * Data for Calculator
     * ----------------------------------------------------------------
     * */
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
                <a name="description"></a>
<?php 

    print($content->arPage["cntBody"]);
?>
                <a name="video"></a>
                <!--div id="video"><iframe width="560" height="315" src="https://www.youtube.com/embed/5PAM0wr7cZ8" frameborder="0" allowfullscreen></iframe></div-->
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