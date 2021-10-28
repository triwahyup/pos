<?php

namespace app\modules\produksi\controllers;

use app\models\Logs;
use app\models\User;
use app\modules\produksi\models\Spk;
use app\modules\produksi\models\SpkDetailProses;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class  HasilController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
				    'rules' => [
                        [
                            'actions' => ['form', 'list-proses', 'update-proses'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('hasil-produksi')),
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionForm()
    {
        $spkProses = new SpkDetailProses();
        $inProgress = Spk::findAll(['status_produksi'=>[1,2,3]]);
        $noSPK = Spk::find()
            ->select(['no_spk'])
            ->where(['status_produksi'=>[1,2,3]])
            ->orderBy(['tgl_spk'=>SORT_DESC])
            ->indexBy('no_spk')
            ->column();
        
        return $this->render('form',[
            'spkProses' => $spkProses,
            'inProgress' => $inProgress,
            'noSPK' => $noSPK,
        ]);
    }

    public function actionListProses($no_spk)
    {
        if(isset($no_spk)){
            $model = SpkDetailProses::find()->where(['no_spk'=>$no_spk, 'status_proses'=>2])->all();
            return json_encode(['data'=>$this->renderPartial('_list_proses', ['model'=>$model])]);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpdateProses()
    {
        $request = \Yii::$app->request;
        $success = true;
        $message = '';
        if($request->isPost){
            $data = $request->post('SpkDetailProses');
            $model = Spk::findOne(['no_spk'=>$data['no_spk'], 'status_produksi'=>3]);
            if(isset($model)){
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try{
                    $model->status_produksi = 1;
                    if($model->save()){
                        foreach($data['urutan'] as $index=>$urutan){
                            $spkProses = SpkDetailProses::findOne(['no_spk'=>$data['no_spk'], 'urutan'=>$urutan, 'status_proses'=>2]);
                            if(isset($spkProses)){
                                $qtyHasil = str_replace(',','', $data['qty_hasil'][$index]);
                                if($qtyHasil <= $spkProses->qty_proses){
                                    $spkProses->qty_hasil = $qtyHasil;
                                    $spkProses->keterangan = $data['keterangan'][$index];
                                    if($spkProses->qty_proses > $spkProses->qty_hasil){
                                        if(!empty($spkProses->keterangan)){
                                            $spkProses->status_proses = 4;
                                        }else{
                                            $success = false;
                                            $message = 'Keterangan untuk '.$spkProses->typeProses() .' ('.$spkProses->mesin->name.') wajib diisi.';
                                        }
                                    }else{
                                        $spkProses->status_proses = 3;
                                    }
                                    if(!$spkProses->save()){
                                        $success = false;
                                        $message = (count($spkProses->errors) > 0) ? 'ERROR CREATE HASIL PRODUKSI: ' : '';
                                        foreach($spkProses->errors as $error => $value){
                                            $message .= strtoupper($value[0].', ');
                                        }
                                        $message = substr($message, 0, -2);
                                    }
                                }else{
                                    $success = false;
                                    $message = 'QTY Hasil tidak boleh lebih besar dari QTY Proses.';
                                }
                            }else{
                                $success = false;
                                $message = 'DATA SPK PROSES NOT VALID.';
                            }
                        }
                    }else{
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE SPK: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= strtoupper($value[0].', ');
                        }
                        $message = substr($message, 0, -2);
                    }

                    if($success){
                        $message = '['.$model->no_spk.'] SUCCESS CREATE HASIL PRODUKSI.';
                        $transaction->commit();
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);
                    }else{
                        $transaction->rollBack();
                    }
                }catch(Exception $e){
                    $success = false;
                    $message = $e->getMessage();
                    $transaction->rollBack();
                }
                $logs =	[
                    'type' => Logs::TYPE_USER,
                    'description' => $message,
                ];
                Logs::addLog($logs);
            }else{
                $success = false;
                $message = 'DATA SPK NOT VALID.';
            }
        }else{
            throw new NotFoundHttpException('The requested data does not exist.');
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }

    protected function findModel($no_spk)
    {
        if (($model = Spk::findOne($no_spk)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}