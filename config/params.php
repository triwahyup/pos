<?php

$data = [
    'DEVELOPER' => ['developer'],
    
    // MASTER KODE TYPE
    'TYPE_MENU' => 'MENU',
    'TYPE_MESIN' => 'MESIN',
    'TYPE_MATERIAL' => 'MATERIAL',
    'TYPE_KERTAS' => 'KERTAS',
    'TYPE_BAHAN_PB' => 'BAHAN PEMBANTU',
    'TYPE_SATUAN' => 'SATUAN',
    'TYPE_USER' => 'USER',
    'TYPE_PERSON' => 'PERSON',
    'TYPE_BAST' => 'BAST',
    'TYPE_KENDARAAN' => 'KENDARAAN',
    'TYPE_PROSES' => 'PROSES',
    
    // MASTER KODE VALUE
    'TYPE_MENU_LEFT' => 'NAVBAR LEFT',
    'TYPE_MENU_TOP' => 'NAVBAR TOP',
    'TYPE_SATUAN_PRODUKSI' => 'PRODUKSI',
    'TYPE_SATUAN_BERAT' => 'BERAT',
    'TYPE_USER_OP_PRODUKSI' => 'OPERATOR PRODUKSI',
    'TYPE_USER_SALES_MARKETING' => 'SALES MARKETING',
    'TYPE_PRODUK_JASA' => 'PRODUK JASA',
    'TYPE_ROLL' => 'ROLL',
    'TYPE_BARANG' => 'BARANG',
    'TYPE_SUP' => 'ADMINISTRATOR',
    
    // MASTER PERSON TYPE
    'TYPE_CUSTOMER' => 1,
    'TYPE_EKSPEDISI' => 2,
    'TYPE_OUTSOURCE' => 3,
    'TYPE_SUPPLIER' => 4,
    'TYPE_SUPPLIER_BARANG' => 5,
    
    // MASTER SATUAN
    'TYPE_RIM_PLANO' => 'RIM-PLANO',
    
    // TYPE STATUS
    'IN_PROGRESS' => 'IN_PROGRESS',
    'IN_REVIEW' => 'IN_REVIEW',
    'DONE' => 'DONE',
    'ON_START' => 'ON_START',
    'ON_FINISH' => 'ON_FINISH',
    'ON_CLOSING' => 'ON_CLOSING',

    'URL' => ((getenv('APP_ENV') != 'production') ? (getenv('APP_ENV') != 'beta') ? 'http://localhost:8080/pos/web/index.php?r=' : '' : ''),
];
return $data;
?>