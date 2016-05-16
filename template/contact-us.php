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
                <h1><%%>Contacts<%%></h1>
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d69648.18742263784!2d8.319605824312326!3d49.55405574050414!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sru!2sus!4v1439567104169" 
                    width="540" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                <form>
                    <br/>
                    <div>
                        <label for="inputEmail">Email</label>
                        <input type="email" id="inputEmail3" placeholder="Email">
                    </div>
                    <br/>
                    <div>
                        <label for="inputName">Name</label>
                        <input type="text" id="inputName" placeholder="Name">
                    </div>
                    <br/>
                    <div>
                        <label for="inputMessage">Message</label>
                        <input type="text" id="inputMessage" placeholder="Message">
                    </div>
                    <br/>
                    <div>
                        <button type="submit">Send</button>
                    </div>
                </form> 
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