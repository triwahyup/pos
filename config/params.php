<?php

$data = [
    'DEVELOPER' => ['developer'],
    'MATERIAL_KERTAS' => 'KERTAS',
    'MATERIAL_TINTA' => 'TINTA',
    'MATERIAL_LAIN' => 'LAIN2',
    'MATERIAL_BP' => 'BAHAN PEMBANTU',
    'MATERIAL_KERTAS_CODE' => '007',
    'MATERIAL_TINTA_CODE' => '008',
    'TYPE_CUSTOMER' => 1,
    'TYPE_SUPPLIER' => 2,
    'TYPE_OUTSOURCE' => 3,
    'TYPE_MENU' => 'MENU',
    'TYPE_USER' => 'USER',
    'TYPE_MATERIAL' => 'MATERIAL',
    'NAVBAR_TOP' => '001',
    'NAVBAR_LEFT' => '002',
    'URL' => ((getenv('APP_ENV') != 'production') ? (getenv('APP_ENV') != 'beta') ? 'http://localhost/pos/web/index.php?r=' : '' : ''),
];
return $data;
?>