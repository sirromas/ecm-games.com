<footer>
    <script type="text/javascript">
        var LHCChatOptions = {};
        LHCChatOptions.opt = {widget_height: 640, widget_width: 300, popup_height: 320, popup_width: 500};
        (function () {
            var po = document.createElement('script');
            po.type = 'text/javascript';
            po.async = true;
            var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://') + 1)) : '';
            var location = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
            po.src = '//ecm-games.com/chat/index.php/rus/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true?r=' + referrer + '&l=' + location;
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(po, s);
        })();
    </script>
    <div class="ft-bt">
        <div id="footermenu">
            <div class="footer-img-center"></div>
            <?php echo "<div class='copyright'>Все права защищены<br />Wd-studio</div>"; ?>
            <div class="footer-img-right"></div>
        </div>        
</footer>
<script type="text/javascript">

<?php echo "<script src='http://" . $_SERVER['SERVER_NAME'] . '/assets/js/tablesorter/sorter.js' . "'></script>"; ?>

    <script type = "text/javascript" >
            jQuery(document).ready(function ($) {
        $("#icon-menu").on("click", function () {
            $(this).toggleClass("active");
            $("#menu ul").toggleClass("toggled");
        });
        $("#icon-category").on("click", function () {
            $(this).toggleClass("active");
            $("#block-category").toggleClass("toggled");
        });

        $('[data-toggle="popover"]').popover({html: true});

    });
</script>
</body>
</html>