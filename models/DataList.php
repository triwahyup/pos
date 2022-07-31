<?php

namespace app\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterProvinsi;
use app\modules\master\models\MasterSatuan;

class DataList {
    
    public static function setListColumn()
    {
        /** FROM MASTER KODE */
        /** DATA KENDARAAN */
        $model['kendaraan'] = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_KENDARAAN'], 'status' => 1])
            ->indexBy('code')
            ->column();
        /** DATA MATERIAL */ 
        $model['material'] = MasterKode::find()
            ->select(['name'])
            ->where(['value'=>[
                    \Yii::$app->params['TYPE_KERTAS'], 
                    \Yii::$app->params['TYPE_BAHAN_PB']
                ], 'status' => 1])
            ->indexBy('code')
            ->column();
        /** DATA BAHAN PB */
        $model['bahan_pembantu'] = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_BAHAN_PB'], 'status' => 1])
            ->indexBy('code')
            ->column();
        /** DATA MESIN */
        $model['mesin'] = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_MESIN'], 'status' => 1])
            ->indexBy('code')
            ->column();
        /** DATA PERSON */
        $model['person'] = MasterKode::find()
            ->select(['name'])
            ->where(['type' => \Yii::$app->params['TYPE_PERSON'], 'status' => 1])
            ->indexBy('value')
            ->column();
        /** DATA PROSES */
        $model['proses'] = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_PROSES'], 'status' => 1])
            ->indexBy('code')
            ->column();
        $model['barang'] = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_BAST'], 'status' => 1])
            ->indexBy('code')
            ->column();
        $model['bast'] = MasterKode::find()
            ->select(['name'])
            ->where(['value'=>[
                    \Yii::$app->params['TYPE_INVENTARIS'],
                    \Yii::$app->params['TYPE_LAIN2']
                ], 'status' => 1])
            ->indexBy('code')
            ->column();
        /** /FROM MASTER KODE */

        /** FROM MASTER PERSON */
        /** DATA CUSTOMER */
        $model['customer'] = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_CUSTOMER'], 'status' => 1])
            ->indexBy('code')
            ->column();
        /** DATA EKSPEDISI */
        $model['ekspedisi'] = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_EKSPEDISI'], 'status' => 1])
            ->indexBy('code')
            ->column();
        /** DATA OUTSOURCE */
        $model['outsource'] = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user' => \Yii::$app->params['TYPE_OUTSOURCE'], 'status'=>1])
            ->indexBy('code')
            ->column();
        /** DATA SUPPLIER */
        $model['supplier'] = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_SUPPLIER'], 'status' => 1])
            ->indexBy('code')
            ->column();
        /** DATA SUPPLIER BARANG */
        $model['supplier_barang'] = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_SUPPLIER_BARANG'], 'status' => 1])
            ->indexBy('code')
            ->column();
        /** /FROM MASTER PERSON */
        
        /** FROM MASTER HIERARKI */
        /** DATA PROVINSI */
        $model['provinsi'] = MasterProvinsi::find()
            ->select(['name'])
            ->where(['status' => 1])
            ->indexBy('id')
            ->column();
        /** /FROM MASTER HIERARKI */

        /** FROM PROFILE */
        /** DATA OPERATOR */
        $model['operator'] = Profile::find()
            ->alias('a')
            ->select(['a.name'])
            ->leftJoin('master_kode b', 'b.code = a.typeuser_code')
            ->where(['value' => \Yii::$app->params['TYPE_USER_OP_PRODUKSI'], 'a.status'=>1])
            ->indexBy('user_id')
            ->column();
        /** DATA SALES */
        $model['sales'] = Profile::find()
            ->alias('a')
            ->select(['a.name'])
            ->leftJoin('master_kode b', 'b.code = a.typeuser_code')
            ->where(['value' => \Yii::$app->params['TYPE_USER_SALES_MARKETING'], 'a.status'=>1])
            ->indexBy('user_id')
            ->column();
        /** DATA USER */
        $model['user'] = Profile::find()
            ->select(['name'])
            ->where(['status' => 1])
            ->indexBy('user_id')
            ->column();
        /** /FROM PROFILE */
        
        /** DATA SATUAN */
        $model['satuan_produksi'] = MasterSatuan::find()
            ->alias('a')
            ->select(['a.name'])
            ->leftJoin('master_kode b', 'b.code = a.type_satuan')
            ->where(['b.value'=>\Yii::$app->params['TYPE_SATUAN_PRODUKSI'], 'a.status'=>1])
            ->indexBy('a.code')
            ->column();
        $model['satuan_berat'] = MasterSatuan::find()
            ->alias('a')
            ->select(['a.name'])
            ->leftJoin('master_kode b', 'b.code = a.type_satuan')
            ->where(['b.value'=>\Yii::$app->params['TYPE_SATUAN_BERAT'], 'a.status'=>1])
            ->indexBy('a.code')
            ->column();
        $model['satuan_barang'] = MasterSatuan::find()
            ->alias('a')
            ->select(['a.name'])
            ->leftJoin('master_kode b', 'b.code = a.type_satuan')
            ->where(['b.value'=>\Yii::$app->params['TYPE_BARANG'], 'a.status'=>1])
            ->indexBy('a.code')
            ->column();
        
        return $model;
    }

    public static function listTypeOngkos()
    {
        return [
            1 => 'Ongkos Kirim',
            2 => 'Penggunaan Plat',
            3 => 'Penggunaan Pisau',
        ];
    }
}