<footer>
    <script type="text/javascript">
        var LHCChatOptions = {};
        LHCChatOptions.opt = {widget_height: 340, widget_width: 300, popup_height: 520, popup_width: 500};
        (function () {
            var po = document.createElement('script');
            po.type = 'text/javascript';
            po.async = true;
            var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://') + 1)) : '';
            var location = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
            po.src = '//mycodebusters.com/games/chat/index.php/rus/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(check_operator_messages)/true/(top)/350/(units)/pixels/(leaveamessage)/true?r=' + referrer + '&l=' + location;
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(po, s);
        })();
    </script>
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
    jQuery(document).ready(function ($) {
        /*
         setTimeout(function () {
         // Do something after 1 second 
         $('html, body').animate({
         scrollTop: $("#icon-menu").offset().top
         }, 1000);
         }, 2500);
         */
    });

</script>

<?php echo "<script src='http://" . $_SERVER['SERVER_NAME'] . '/games/assets/js/tablesorter/sorter.js' . "'></script>"; ?>

<script type="text/javascript">
    
    jQuery(document).ready(function ($) {
        $("#icon-menu").on("click", function () {
            $(this).toggleClass("active");
            $("#menu ul").toggleClass("toggled");
        });
        $("#icon-category").on("click", function () {
            $(this).toggleClass("active");
            $("#block-category").toggleClass("toggled");
        });
        
        
    });
</script>
</body>
</html>