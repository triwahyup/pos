<?php

namespace app\modules\master\controllers;

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
use app\modules\master\models\ProfileSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * ProfileController implements the CRUD actions for Profile model.
 */
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
                            'actions' => ['create'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-user[C]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['index', 'view', 'list-kabupaten', 'list-kecamatan', 'list-kelurahan'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-user[R]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-user[U]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-user[D]')),
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['switch'],
                            'allow' => (((new User)->getIsDeveloper())),
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

    public function actionSwitch($user_id)
    {
        $initialId = \Yii::$app->user->getId();
        if($user_id == $initialId){
            $message = 'Tidak bisa switch user menggunakan user yang sama.';
            \Yii::$app->session->setFlash('danger', $message);
            $logs = [
				'type' => Logs::TYPE_USER,
				'user' => $initialId,
				'description' => "{".$user_id."} SWITCH USER! ".$message,
			];
			Logs::addLog($logs);
        }else{
            $profile = $this->findModel($user_id);
            if(\Yii::$app->user->identity->profile->typeUser->value == 'ADMINISTRATOR'){
                if(!$profile->user->validateBlocked()){
                    $duration = 0;
                    $logs = [
                        'type' => Logs::TYPE_USER,
                        'user' => $initialId,
                        'description' => '{'.$user_id.'} Switch to User "'.$profile->name.'"',
                    ];
                    Logs::addLog($logs);
    
                    \Yii::$app->user->switchIdentity($profile->user, $duration); 
                    \Yii::$app->session->set('user.idbeforeswitch', $initialId);
                    return $this->goHome();
                }else{
                    $message = 'Switch Failed, user '.$profile->name.' is already blocked.';
                    \Yii::$app->session->setFlash('danger', $message);
                    $logs = [
                        'type' => Logs::TYPE_USER,
                        'user' => $initialId,
                        'description' => '{'.$user_id.'} '.$message,
                    ];
                    Logs::addLog($logs);
                }
            }else{
                \Yii::$app->session->setFlash('danger', 'Anda tidak mempunyai akses untuk melakukan switch user.');
            }
        }
        return $this->redirect(['index']);
    }

    /**
     * Lists all Profile models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProfileSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Profile model.
     * @param int $user_id User ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id),
        ]);
    }

    /**
     * Creates a new Profile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
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
        $model = new Profile();
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $connection = \Yii::$app->db;
                $transaction = $connection->beginTransaction();
                try {
                    $user = new User();
                    $user->attributes = $model->attributes;
                    if(empty($model->username)){
                        $user->username = 'user_'.date('is');
                    }else{
                        $user->username = $model->username;
                    }
                    if(empty($model->password)){
                        $user->password_hash = \Yii::$app->security->generatePasswordHash('*#');
                    }else{
                        $user->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
                    }
                    $user->auth_key = \Yii::$app->security->generateRandomString();
                    $user->registration_ip = \Yii::$app->request->userIP;
                    $user->confirmed_at = time();
                    $user->created_at = time();
                    $user->updated_at = time();
                    if($user->save()){
                        $authItems = AuthItemChild::findAll(['parent'=>str_replace(' ','-', $model->typeUser->value)]);
                        if(count($authItems) > 0){
                            foreach($authItems as $val){
                                $authAssignment = new AuthAssignment();
                                $authAssignment->item_name = $val->child;
                                $authAssignment->user_id = $user->id;
                                $authAssignment->created_at = time();
                                if(!$authAssignment->save()){
                                    $success = false;
                                    $message = (count($authAssignment->errors) > 0) ? 'ERROR CREATE AUTH : ' : '';
                                    foreach($authAssignment->errors as $error => $value){
                                        $message .= $value[0].', ';
                                    }
                                    $message = substr($message, 0, -2);
                                }
                            }
                        }
                        
                        $model->user_id = $user->id;
                        $model->phone_1 = str_replace('-', '', $model->phone_1);
                        if(!empty($model->phone_2)){
                            $model->phone_2 = str_replace('-', '', $model->phone_2);
                        }
                        if(!$model->save()){
                            $success = false;
                            $message = (count($model->errors) > 0) ? 'ERROR CREATE DATA USER: ' : '';
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
                        $message = '['.$model->user_id.'] SUCCESS CREATE USER.';
                        $logs =	[
                            'type' => Logs::TYPE_USER,
                            'description' => $message,
                        ];
                        Logs::addLog($logs);

                        \Yii::$app->session->setFlash('success', $message);
                        return $this->redirect(['view', 'user_id' => $model->user_id]);
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
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'dataProvinsi' => $dataProvinsi,
            'typeUser' => $typeUser,
        ]);
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $user_id User ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($user_id)
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
        $user = User::findOne(['id'=>$user_id]);
        $model = $this->findModel($user_id);
        $model->username = $user->username;
        if ($this->request->isPost && $model->load($this->request->post())) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                AuthAssignment::deleteAll('user_id=:user_id', [':user_id'=>$user_id]);
                $authItems = AuthItemChild::findAll(['parent'=>str_replace(' ','-', $model->typeUser->value)]);
                if(count($authItems) > 0){
                    foreach($authItems as $val){
                        $authAssignment = new AuthAssignment();
                        $authAssignment->item_name = $val->child;
                        $authAssignment->user_id = $user_id;
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
                    return $this->redirect(['view', 'user_id' => $model->user_id]);
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

    /**
     * Deletes an existing Profile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $user_id User ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($user_id)
    {
        $success = true;
        $message = '';
        $model = $this->findModel($user_id);
        if(isset($model)){
            $connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();
            try{
                $model->status=0;
                if(!$model->save()){
                    $success = false;
                    $message = (count($model->errors) > 0) ? 'ERROR DELETE DATA USER: ' : '';
                    foreach($model->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                $user = User::findOne(['id'=>$user_id]);
                $user->status = 0;
                $user->blocked_at = time();
                if(!$user->save()){
                    $success = false;
                    $message = (count($user->errors) > 0) ? 'ERROR BLOCK USER: ' : '';
                    foreach($user->errors as $error => $value){
                        $message .= $value[0].', ';
                    }
                    $message = substr($message, 0, -2);
                }

                if($success){
                    AuthAssignment::deleteAll('user_id=:user_id', [':user_id'=>$user_id]);
                    $transaction->commit();
                    $message = '['.$model->user_id.'] SUCCESS DELETE USER.';
                    \Yii::$app->session->setFlash('success', $message);
                }else{
                    $transaction->rollBack();
                    \Yii::$app->session->setFlash('error', $message);
                }
            }catch(\Exception $e){
				$success = false;
				$message = $e->getMessage();
				$transaction->rollBack();
                \Yii::$app->session->setFlash('error', $message);
            }
            $logs =	[
                'type' => Logs::TYPE_USER,
                'description' => $message,
            ];
            Logs::addLog($logs);
        }
        return $this->redirect(['index']);
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

    public function actionListKabupaten($provinsiId)
    {
        if(isset($provinsiId)){
            $model = MasterKabupaten::find()
                ->select(['id', 'name'])
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
