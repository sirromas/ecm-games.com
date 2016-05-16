    <nav  class="navbar-default navbar-side" role="navigation">
      <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
          <li><div class="user-img-div"><img src="/<?= $config -> get_admin_template_fullpath() ?>assets/img/user.jpg" class="img-circle" /></div></li>
          <li><a href="#"> <strong> Romelia Alexendra </strong></a></li>
<?php
    function recursive_admin_menu($els, $currentlevel = 1)
    {
      global $config;
      $arRecurseClasse = array(1 => "", 2=>' class="nav nav-second-level"', 3=>' class="nav nav-third-level"', );

      if (isset($els) && is_array($els) && count($els) > 0)
      {
        foreach($els as $el)
        {
          if (is_array($el) && count($el) > 0 && $el['visible'])
          {?>
              <li><a <?php
              if ($config -> content['pathinfo']['basename'] == $el['templatefile'])
              {
                echo 'class="active-menu" ';
              }
              ?>href="<?= $el['templatefile'] == '' || $el['templatefile'] == '#' ? "#" : "/" . $config -> get_admin_controller_fullpath() . $el['templatefile'] ?>"><?php
            if ($el['icon'] != "")
            {    
              ?><i class="fa fa-<?= $el['icon'] ?> "></i><?php
            }  
            echo $el['Name'];
            if (isset($el['child']) && is_array($el['child']) && count($el['child']) > 0)
            {
              $currentlevel++;
              ?><span class="fa arrow"></span></a>
                <ul<?= $arRecurseClasse[$currentlevel]?>>
<?php
              recursive_admin_menu($el['child'], $currentlevel);?>
                </ul>
<?
            }
            ?></a></li>
<?php
          }
        }
      }
      return;
    }

  if (isset($arAdmin) && is_array($arAdmin) && count($arAdmin) > 0)
  {
    recursive_admin_menu($arAdmin);
  }
?>
        </ul>            
      </div>
    </nav>
    <!-- /. SIDEBAR MENU (navbar-side) -->
