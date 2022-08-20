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
    'admin_languages_setlang_index' => '\\Opoink\\Bmodule\\Controller\\Admin\\Languages\\Setlang\\Index',
    'admin_logout_index_index' => '\\Opoink\\Bmodule\\Controller\\Admin\\Logout\\Index\\Index',
    'admin_settings_savesettings_index' => '\\Opoink\\Bmodule\\Controller\\Admin\\Settings\\Savesettings\\Index',
    'admin_adminuser_add_index' => '\\Opoink\\Bmodule\\Controller\\Admin\\Adminuser\\Add\\Index',
    'admin_adminuser_save_index' => '\\Opoink\\Bmodule\\Controller\\Admin\\Adminuser\\Save\\Index',
    'admin_adminuser_edit_index' => '\\Opoink\\Bmodule\\Controller\\Admin\\Adminuser\\Edit\\Index',
    'admin_uicomponents_grid_listing' => '\\Opoink\\Bmodule\\Controller\\Admin\\Uicomponents\\Grid\\Listing',
    'admin_grid_export_csvfile' => '\\Opoink\\Bmodule\\Controller\\Admin\\Grid\\Export\\Csvfile',
  ),
)
?>