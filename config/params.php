<?php

$data = [
    'DEVELOPER' => ['developer'],
    
    // TYPE CODE
    'TYPE_MATERIAL' => 'MATERIAL',
    'TYPE_MENU' => 'MENU',
    'TYPE_MESIN' => 'MESIN',
    'TYPE_SATUAN' => 'SATUAN',
    'TYPE_USER' => 'USER',
    // TYPE CODE VAL
    'TYPE_MATERIAL_BP' => 'BAHAN PEMBANTU',
    'TYPE_MATERIAL_KERTAS' => 'KERTAS',
    'TYPE_MENU_LEFT' => 'NAVBAR LEFT',
    'TYPE_MENU_TOP' => 'NAVBAR TOP',
    'TYPE_SATUAN_PRODUKSI' => 'PRODUKSI',
    // TYPE PERSON
    'TYPE_CUSTOMER' => 1,
    'TYPE_OUTSOURCE' => 3,
    'TYPE_SUPPLIER' => 2,
    // TYPE JASA
    'TYPE_PRODUK_JASA' => '021',

    'URL' => ((getenv('APP_ENV') != 'production') ? (getenv('APP_ENV') != 'beta') ? 'http://localhost/pos/web/index.php?r=' : '' : ''),
];
return $data;
?>