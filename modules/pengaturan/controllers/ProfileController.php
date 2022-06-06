<?php

namespace app\modules\pengaturan\controllers;

use app\models\AuthAssignment;
use app\models\AuthItemChild;
use app\models\Logs;
use app\models\User;
use app\modules\master\models\MasterKode;
use app\modules\master\models\MasterKabupaten;
use app\modules\master\models\MasterKecamatan;
use app\modules\master\models\MasterKelurahan;
use app\modules\master\models\MasterProvinsi;
use app\modules\master\models\Profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ProfileController extends Controller
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
                            'actions' => ['list-kabupaten', 'list-kecamatan', 'list-kelurahan'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-profile')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-profile')),
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

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $user_id User ID
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id)
    {
        if (($model = Profile::findOne($user_id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $user_id User ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $dataProvinsi = MasterProvinsi::find()
            ->select(['name'])
            ->where(['status' => 1])
            ->indexBy('id')
            ->column();
        $typeUser = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>\Yii::$app->params['TYPE_USER'], 'status' => 1])
            ->indexBy('code')
            ->column();

        $success = true;
        $message = '';
        $user = User::findOne(['id'=>\Yii::$app->user->id]);
        $model = $this->findModel(\Yii::$app->user->id);
        $model->username = $user->username;
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                AuthAssignment::deleteAll('user_id=:user_id', [':user_id'=>\Yii::$app->user->id]);
                $authItems = AuthItemChild::findAll(['parent'=>str_replace(' ','-', $model->typeUser->value)]);
                if(count($authItems) > 0){
                    foreach($authItems as $val){
                        $authAssignment = new AuthAssignment();
                        $authAssignment->item_name = $val->child;
                        $authAssignment->user_id = \Yii::$app->user->id;
                        $authAssignment->created_at = time();
                        if(!$authAssignment->save()){
                            $success = false;
                            $message = (count($authAssignment->errors) > 0) ? 'ERROR UPDATE AUTH : ' : '';
                            foreach($authAssignment->errors as $error => $value){
                                $message .= $value[0].', ';
                            }
                            $message = substr($message, 0, -2);
                        }
                    }
                }

                $user->email = $model->email;
                $user->updated_at = time();
                if(!empty($model->current_password)){
                    $user->password = $model->new_password;
                }
                if($user->save()){
                    $model->phone_1 = str_replace('-', '', $model->phone_1);
                    if(!empty($model->phone_2)){
                        $model->phone_2 = str_replace('-', '', $model->phone_2);
                    }
                    if(!$model->save()){
                        $success = false;
                        $message = (count($model->errors) > 0) ? 'ERROR UPDATE DATA USER: ' : '';
                        foreach($model->errors as $error => $value){
                            $message .= $value[0].', ';
                        }
                        $message = substr($message, 0, -2);
                    }
                }else{
                    $success = false;
                    $message = (count($user->errors) > 0) ? 'ERROR CREATE USER: ' : '';
                    foreach($user->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }
                if($success){
                    $transaction->commit();
                    $message = '['.$model->user_id.'] SUCCESS UPDATE USER.';
                    $logs =	[
                        'type' => Logs::TYPE_USER,
                        'description' => $message,
                    ];
                    Logs::addLog($logs);

                    \Yii::$app->session->setFlash('success', $message);
                    return $this->redirect(['update']);
                }else{
                    $transaction->rollBack();
                }
            }catch(\Exception $e) {
                $success = false;
                $message = $e->getMessage();
                $transaction->rollBack();
            }
            $logs =	[
                'type' => Logs::TYPE_USER,
                'description' => $message,
            ];
            Logs::addLog($logs);
            \Yii::$app->session->setFlash('error', $message);
        }

        return $this->render('update', [
            'model' => $model,
            'dataProvinsi' => $dataProvinsi,
            'typeUser' => $typeUser,
        ]);
    }

    public function actionListKabupaten($provinsiId)
    {
        if(isset($provinsiId)){
            $model = MasterKabupaten::find()
                ->select(['name', 'id'])
                ->where(['provinsi_id'=>$provinsiId, 'status'=>1])
                ->asArray()
                ->all();
            return json_encode($model);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListKecamatan($kecamatanId)
    {
        if(isset($kecamatanId)){
            $model = MasterKecamatan::find()
                ->select(['id', 'name'])
                ->where(['kabupaten_id'=>$kecamatanId, 'status'=>1])
                ->asArray()
                ->all();
            return json_encode($model);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionListKelurahan($kelurahanId)
    {
        if(isset($kelurahanId)){
            $model = MasterKelurahan::find()
                ->select(['id', 'name'])
                ->where(['kecamatan_id'=>$kelurahanId, 'status'=>1])
                ->asArray()
                ->all();
            return json_encode($model);
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}