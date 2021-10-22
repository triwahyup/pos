<?php

$data = [
    'DEVELOPER' => ['developer'],
    'MATERIAL_KERTAS' => 'KERTAS',
    'MATERIAL_BP' => 'BAHAN PEMBANTU',
    'MATERIAL_KERTAS_CODE' => '007',
    'MATERIAL_BP_CODE' => '010',
    'TYPE_CUSTOMER' => 1,
    'TYPE_SUPPLIER' => 2,
    'TYPE_OUTSOURCE' => 3,
    'TYPE_MENU' => 'MENU',
    'TYPE_USER' => 'USER',
    'TYPE_MATERIAL' => 'MATERIAL',
    'TYPE_MESIN' => 'MESIN',
    'NAVBAR_TOP' => '001',
    'NAVBAR_LEFT' => '002',
    'URL' => ((getenv('APP_ENV') != 'production') ? (getenv('APP_ENV') != 'beta') ? 'http://localhost/pos/web/index.php?r=' : '' : ''),
];
return $data;
?>