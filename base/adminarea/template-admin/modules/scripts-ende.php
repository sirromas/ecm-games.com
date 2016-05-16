
  <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
  <!-- JQUERY SCRIPTS -->
  <script src="/<?= $config -> get_admin_template_fullpath()?>assets/js/jquery-1.11.1.js"></script>
  <!-- BOOTSTRAP SCRIPTS -->
  <script src="/<?= $config -> get_admin_template_fullpath()?>assets/js/bootstrap.js"></script>
  <!-- METISMENU SCRIPTS -->
  <script src="/<?= $config -> get_admin_template_fullpath()?>assets/js/jquery.metisMenu.js"></script>
<?php
    if ($bTinyMCE)
    {
?>  <script type="text/javascript" src="/<?= $config -> get_admin_template_fullpath()?>assets/js/tinymce/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: ".textareaMCE"
        });
    </script>
<?php
    }
?>
    <script src="/<?= $config -> get_admin_template_fullpath()?>assets/js/jquery.confirm.min.js"></script>
    <script src="/<?= $config -> get_admin_template_fullpath()?>assets/js/jquery.msgbox/jquery.msgbox.js"></script>

  <!-- CUSTOM SCRIPTS -->
  <script src="/<?= $config -> get_admin_template_fullpath()?>assets/js/custom.js"></script>
