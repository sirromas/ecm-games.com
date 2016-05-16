<?php
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
                <h1><?= htmlspecialchars(isset($content->arPage['cntMetaTitle']) && $content->arPage['cntMetaTitle'] ? $content->arPage['cntMetaTitle'] : "<%%>Request for currrency sale<%%>")?></h1>
                <div>
                    <?= isset($content->arPage['cntBody']) ? $content->arPage['cntBody'] : ""?>
                </div>
<?php
    require_once file_translate("template/modules/sell-form.php");
?>
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