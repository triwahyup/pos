<?php

namespace app\modules\master\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterBiayaProduksi;
use app\modules\master\models\MasterMaterialItem;
use app\modules\master\models\MasterOrder;
use app\modules\master\models\MasterOrderDetail;
use app\modules\master\models\MasterOrderDetailProduksi;
use app\modules\master\models\MasterSatuan;
use app\modules\master\models\TempMasterOrderDetail;
use app\modules\master\models\TempMasterOrderDetailProduksi;
use app\modules\master\models\MasterOrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for MasterOrder model.
 */
class OrderController extends Controller
{
    public function actionListBiaya($order_code, $item_code, $urutan)
    {
        $data = [];
        $model = MasterBiayaProduksi::find()->where(['status'=>1])->all();
        foreach($model as $val){
            $data[$val->code] = [
                'name' => $val->name,
                'biaya' => $val->code,
                'type' => $val->type,
                'order' => $order_code,
                'item' => $item_code,
                'urutan' => $urutan,
                'harga' => $val->harga,
            ];
        }
        $temps = TempMasterOrderDetailProduksi::findAll(['order_code'=>$order_code, 'item_code'=>$item_code, 'user_id'=>\Yii::$app->user->id]);
        if(count($temps) > 0){
            foreach($temps as $val){
                $data[$val->biaya_produksi_code] = [
                    'id' => $val->id,
                    'order' => $order_code,
                    'name' => $val->name,
                    'biaya' => $val->biaya_produksi_code,
                    'item' => $val->item_code,
                    'urutan' => $urutan,
                    'type' => $val->type,
                    'harga' => $val->harga,
                ];
            }
        }
        return json_encode(['data'=>$this->renderPartial('_list_biaya', ['data'=>$data])]);
    }

    
    public function actionCreateTempProduksi()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = 'CREATE DETAIL PROSES SUCCESSFULLY';
        $temp = new TempMasterOrderDetailProduksi();
        if($request->isPost){
            $data = $request->post();
            $tmp = $request->post('Temp');
            TempMasterOrderDetailProduksi::deleteAll('order_code=:order and item_code=:item and user_id=:user and type=1', [
                ':order'=>$tmp['order_code'],
                ':item'=>$tmp['item_code'],
                ':user'=>\Yii::$app->user->id,
            ]);
            $detail = TempMasterOrderDetail::findOne(['order_code'=>$tmp['order_code'], 'item_code'=>$tmp['item_code'], 'urutan'=>$tmp['urutan']]);
            foreach($data['biaya'] as $val){
                $temp = new TempMasterOrderDetailProduksi();
                $biaya = MasterBiayaProduksi::findOne(['code'=>$val, 'status'=>1]);
                
                $temp->attributes = (array)$tmp;
                $temp->attributes = $detail->attributes;
                $temp->attributes = $biaya->attributes;
                $temp->biaya_produksi_code = $val;
                $temp->urutan = $temp->count +1;
                $temp->user_id = \Yii::$app->user->id;
                if(!$temp->save()){
                    $success = false;
                    foreach($temp->errors as $error => $value){
                        $message = $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    protected function findTempProduksi($id)
    {
        $temp = TempMasterOrderDetailProduksi::findOne(['id'=>$id, 'user_id'=>\Yii::$app->user->id]);
        if(isset($temp)){
            return $temp;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
