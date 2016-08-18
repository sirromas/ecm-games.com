
<?php
$url = $_SERVER['REQUEST_URI'];
if (array_key_exists('email', $_SESSION) == FALSE) {
    if (!strpos($url, 'news') === FALSE) {
        echo "<br><div style='float: left;
    margin: 0 3%;
    text-align: center;
    width: 74%;'>";
        //echo "<div id='main'>";
    } // end if strpos($url, 'news') === TRUE    
    else {
        echo "<div id='main'>";
    } // end else
} // end if
else {
    if (!strpos($url, 'index.php') === FALSE) {
        echo "<br><div style='float: left;
    margin: 0 3%;
    text-align: center;
    width: 74%;'>";
    } // end if !strpos($url, 'index.php') === FALSE    
    else {
        echo "<div id='main'>";
    }
} // end else
?>

<!--<div id='main'>-->

<script src='http://mycodebusters.com/games/ckeditor/ckeditor.js'></script>
<?php
echo $page;
?>
</div>