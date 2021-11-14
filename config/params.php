<?php

$data = [
    'DEVELOPER' => ['developer'],

    'MATERIAL_BP' => 'BAHAN PEMBANTU',
    'MATERIAL_KERTAS' => 'KERTAS',
    
    'MATERIAL_BP_CODE' => '010',
    'MATERIAL_KERTAS_CODE' => '007',
    
    'NAVBAR_LEFT' => '002',
    'NAVBAR_TOP' => '001',

    'TYPE_CUSTOMER' => 1,
    'TYPE_OUTSOURCE' => 3,
    'TYPE_SUPPLIER' => 2,

    'TYPE_MATERIAL' => 'MATERIAL',
    'TYPE_MESIN' => 'MESIN',
    'TYPE_MENU' => 'MENU',
    'TYPE_SATUAN' => 'SATUAN',
    'TYPE_USER' => 'USER',

    'URL' => ((getenv('APP_ENV') != 'production') ? (getenv('APP_ENV') != 'beta') ? 'http://localhost/pos/web/index.php?r=' : '' : ''),
];
return $data;
?>