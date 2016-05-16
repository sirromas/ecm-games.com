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
                <h1><?= htmlspecialchars($content->arPage['cntMetaTitle'])?></h1>
<?php
    if ($content->arPage['cntID'] == 21)
    {
?>
        <div class="category-img">
            <nav>
<?php 
    if ($gamesPages) 
    {
        $countPunkt = count($gamesPages);
        foreach($gamesPages as $i => $punkt)
        {
            print("
            <a href=\"".getNewURL("-", $content->getFullUrl($punkt['cntID']))."\" title=\"" . htmlspecialchars($punkt['cntTitle']) ."\"><img src=\"/template/icon/".$punkt['cntFileName'].".jpg\" title=\"" . htmlspecialchars($punkt['cntTitle']) ."\" alt=\"" . htmlspecialchars($punkt['cntTitle']) ."\"/></a>");
        }
    }
?>
            </nav>
        </div>

<?php

    }
?>
                <div>
                    <?= isset($content->arPage['cntBody']) ? $content->arPage['cntBody'] : ""?>
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