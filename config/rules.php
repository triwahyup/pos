<?php 

return [
    'dashboard' => 'site/index',
    'navbar-top' => 'site/navbar-top',
    'navbar-left' => 'site/navbar-left',

    'pengaturan/menu' => 'pengaturan/menu/index',
    'pengaturan/menu-view/<id:\d+>' => 'pengaturan/menu/view',
    'pengaturan/menu-create' => 'pengaturan/menu/create',
    'pengaturan/menu-update/<id:\d+>' => 'pengaturan/menu/update',
    'pengaturan/menu-delete/<id:\d+>' => 'pengaturan/menu/delete',
    'pengaturan/menu/list-parent-menu' => 'pengaturan/menu/parent-menu',
];

?>
