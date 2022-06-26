<?php

namespace app\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterPerson;
use app\modules\master\models\MasterSatuan;

class DataList {
    
    public static function setListColumn()
    {
        $model['customer'] = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_CUSTOMER'], 'status' => 1])
            ->indexBy('code')
            ->column();
        $model['ekspedisi'] = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user'=>\Yii::$app->params['TYPE_EKSPEDISI'], 'status' => 1])
            ->indexBy('code')
            ->column();
        $model['outsource'] = MasterPerson::find()
            ->select(['name'])
            ->where(['type_user' => \Yii::$app->params['TYPE_OUTSOURCE'], 'status'=>1])
            ->indexBy('code')
            ->column();
        $model['operator'] = Profile::find()
            ->alias('a')
            ->select(['a.name'])
            ->leftJoin('master_kode b', 'b.code = a.typeuser_code')
            ->where(['value' => \Yii::$app->params['TYPE_USER_OP_PRODUKSI'], 'a.status'=>1])
            ->indexBy('user_id')
            ->column();
        $model['sales'] = Profile::find()
            ->alias('a')
            ->select(['a.name'])
            ->leftJoin('master_kode b', 'b.code = a.typeuser_code')
            ->where(['value' => \Yii::$app->params['TYPE_USER_SALES_MARKETING'], 'a.status'=>1])
            ->indexBy('user_id')
            ->column();
        $model['satuan'] = MasterSatuan::find()
            ->alias('a')
            ->select(['a.name'])
            ->leftJoin('master_kode b', 'b.code = a.type_satuan')
            ->where(['b.value'=>\Yii::$app->params['TYPE_SATUAN_PRODUKSI'], 'a.status'=>1])
            ->indexBy('a.code')
            ->column();

        $model['kendaraan'] = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_KENDARAAN'], 'status' => 1])
            ->indexBy('code')
            ->column();
        return $model;
    }
}