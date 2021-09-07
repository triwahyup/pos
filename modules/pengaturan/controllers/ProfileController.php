<?php

namespace app\modules\pengaturan\controllers;

use app\commands\Konstanta;
use app\models\Logs;
use app\models\User;
use app\models\Profile;
use app\modules\master\models\MasterKode;
use app\modules\pengaturan\models\PengaturanGantiPassword;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

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
                            'actions' => ['username'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('ganti-username')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['password'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('ganti-password')),
                            'roles' => ['@'],
                        ], 
                    ],
                ],
            ]
        );
    }

    public function actionUsername()
    {
        $message = '';
        $model = $this->findModel(\Yii::$app->user->id);
        $profile = Profile::findOne(['user_id'=>\Yii::$app->user->id]);
        $typeCode = MasterKode::find()
            ->select(['name'])
            ->where(['type'=>Konstanta::TYPE_USER, 'status'=>1])
            ->indexBy('code')
            ->column();
        
        if ($this->request->isPost && $model->load($this->request->post()) && $profile->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $model->username = $model->username;
                $profile->attributes = $profile->attributes;
                if($model->save() && $profile->save()){
                    $message = 'SUCCESS GANTI USER / PROFILE: {'.$model->username.', '.$profile->name .'}';
                    $transaction->commit();
                    \Yii::$app->session->setFlash('success', $message);
                }else{
                    $message = (count($model->errors) > 0) ? 'ERROR GANTI USERNAME' : '';
                    foreach($model->errors as $error=>$value){
                        $message .= strtoupper($error).": ".$value[0].', ';
                    }
                    $message = (count($profile->errors) > 0) ? 'ERROR GANTI PROFILE' : '';
                    foreach($profile->errors as $error=>$value){
                        $message .= strtoupper($error).": ".$value[0].', ';
                    }
                    $message = substr($message,0,-2);
                    \Yii::$app->session->setFlash('error', $message);
                }
                return $this->refresh();
            }catch(\Exception $e) {
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

        return $this->render('username', [
            'profile' => $profile,
            'model' => $model,
            'typeCode' => $typeCode,
        ]);
    }

    public function actionPassword()
    {
        $message = '';
        $user = $this->findModel(\Yii::$app->user->id);
        $model = new PengaturanGantiPassword();
        if ($this->request->isPost && $model->load($this->request->post())) {
            if($model->validate()){
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    $user->password = $model->new_password;
                    if($user->save()){
                        $message = 'SUCCESS GANTI PASSWORD: username {'.$user->username.'}';
                        $transaction->commit();
                        \Yii::$app->session->setFlash('success', $message);
                    }else{
                        $message = (count($user->errors) > 0) ? 'ERROR GANTI PASSWORD' : '';
                        foreach($user->errors as $error=>$value){
                            $message .= strtoupper($error).": ".$value[0].', ';
                        }
                        $message = substr($message,0,-2);
                        \Yii::$app->session->setFlash('error', $message);
                    }
                    return $this->refresh();
                }catch(\Exception $e) {
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
        }

        return $this->render('password', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}