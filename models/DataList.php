<?php

namespace app\models;

use Yii;
use app\models\Profile;
use app\modules\master\models\MasterPerson;

class DataList {
    
    public function setListColumn()
    {
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
        return $model;
    }
}