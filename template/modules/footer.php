    <footer>
        <div class="ft-bt">
            <div id="footermenu">
            <div class="footer-img-left"></div>
            <div class="copyright">
<?php 
    $footerMenuPages = $content -> getChildsPages($config->content['footer']);
    if ($footerMenuPages) 
    {
        $countPunkt = count($footerMenuPages);
?>
            <div id="footermenu">
<?php
        foreach($footerMenuPages as $i => $punkt)
        {
            print("<a href=\"".getNewURL("-", $punkt['cntFileName'])."\" title=\"" . htmlspecialchars($punkt['cntTitle']) ."\">".$punkt['cntTitle']."</a>");
            if(($countPunkt-1) > $i) 
            {
                print("&nbsp;|&nbsp;");
            }
        }
?>
            </div>
<?php 
    }
?>
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
