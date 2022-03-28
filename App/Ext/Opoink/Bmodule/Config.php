<?php
return array (
  'vendor' => 'Opoink',
  'module' => 'Bmodule',
  'version' => '1.0.0',
  'controllers' => 
  array (
    'admin_index_index_index' => '\\Opoink\\Bmodule\\Controller\\Admin\\Index\\Index\\Index',
    'admin_adminuser_index_index' => '\\Opoink\\Bmodule\\Controller\\Admin\\Adminuser\\Index\\Index',
    'admin_settings_allsettings_index' => '\\Opoink\\Bmodule\\Controller\\Admin\\Settings\\Allsettings\\Index',
    0 => 
    array (
      'pattern' => '/opoink/bmodule/admin/vue/component/allcomponents',
      'class' => 'Opoink\\Bmodule\\Controller\\Opoink\\Bmodule\\Admin\\Vue\\Component\\Allcomponents',
      'page_name' => 'Opoink\\Bmodule\\Admin\\Vue\\Component\\Allcomponents',
      'method' => 'GET',
    ),
    1 => 
    array (
      'pattern' => '/opoink/bmodule/admin/vue/component/:componentname',
      'class' => 'Opoink\\Bmodule\\Controller\\Opoink\\Bmodule\\Admin\\Vue\\Component\\ComponentName',
      'page_name' => 'Opoink\\Bmodule\\Admin\\Vue\\Component\\ComponentName',
      'method' => 'GET',
    ),
    'admin_login_index_index' => '\\Opoink\\Bmodule\\Controller\\Admin\\Login\\Index\\Index',
  ),
)
?>