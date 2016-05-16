 <footer>
        <div class="ft-bt">
            <div id="footermenu">
            <div class="footer-img-left"></div>
            <div class="copyright">
            Все права защищены<br />Wd-studio</div>
            <div class="footer-img-right"></div>
        </div>
        <!--div class="footer-b"></div-->
    </footer>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            setTimeout( function(){ 
                // Do something after 1 second 
                $('html, body').animate({
                    scrollTop: $("#icon-menu").offset().top
                }, 1000);
            }  , 2500 );
        });
    </script>

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