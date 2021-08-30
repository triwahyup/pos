<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use app\models\AuthItem;
use app\models\AuthItemChild;
use app\models\AuthAssignment;
use app\models\User;
use app\models\UserSearch;
use app\models\Logs;

class UserController extends Controller
{
    public function behaviors()
	{
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'switch', 'block'],
                        'allow' => ((new User)->getIsDeveloper() || yii::$app->user->can('user')),
                        'roles' => ['@'],
                    ], 
					[
                        'actions' => ['type', 'create', 'update', 'delete'],
                        'allow' => ((new User)->getIsDeveloper() || yii::$app->user->can('type-user')),
                        'roles' => ['@'],
                    ], 
                ],
            ],
			'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'switch, block, reset-password, delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
	{
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($username)
	{
		if(($model = User::find()->where(['username'=>$username])->one()) !== null) {
			return $this->render('view', [
				'model' => $model->profile,
			]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
	}

    public function actionSwitch($id)
	{
		$initialId = Yii::$app->user->getId();
		if ($id == $initialId) {
			Yii::$app->session->setFlash('warning', "Can't swicth, same user !");
			$logs=	[
				'type' => Logs::TYPE_USER,
				'user' => $initialId,
				'description' => "{".$id."} SWITCH USER! Can't swicth, same user !",
			];
			Logs::addLog($logs);
		}else{
			$user = $this->findModel($id);
			if(!$user->validateBlocked()){				
				$duration = 0;
				
				$logs=	[
					'type' => Logs::TYPE_USER,
					'user' => $initialId,
					'description' => '{'.$id.'} Switch to User "'.$user->profile->name.'"',
				];
				Logs::addLog($logs);
				
				Yii::$app->user->switchIdentity($user, $duration); 
				Yii::$app->session->set('user.idbeforeswitch', $initialId);
				return $this->goHome();
			}else{
				Yii::$app->session->setFlash('warning', 'Switch Failed, user "'.$user->profile->name.'" is already blocked');
				$logs=	[
					'type' => Logs::TYPE_USER,
					'user' => $initialId,
					'description' => '{'.$id.'} Switch Failed, user "'.$user->profile->name.'" is already blocked',
				];
				Logs::addLog($logs);
			}
		}
		return $this->redirect(['index']);
	}
	
	public function actionBlock($id)
	{
		if ($id == \Yii::$app->user->getId()) {
            Yii::$app->getSession()->setFlash('warning', 'You can not block your own account');
			$message = "You can not block your own account";
        }else{			
			$user = $this->findModel($id);
			if(!$user->blocked_at){
				$user->blocked_at = time();
				if($user->save()){
					$message = 'Block user "'.$user->profile->name.'" success !';
					Yii::$app->session->setFlash('success', $message);
				}else{
					$message = 'Block is failed. Please contact Administrator !';
					Yii::$app->session->setFlash('warning', $message);
				}
			}else{
				$user->blocked_at = NULL;
				if($user->save()){
					$message = 'Unblock user "'.$user->profile->name.'" success!';
					Yii::$app->session->setFlash('success', $message);
				}else{
					$message = 'Unblock is failed. Please contact Administrator !';
					Yii::$app->session->setFlash('warning', $message);
				}
			}
		}
		$logs=	[
			'type' => Logs::TYPE_USER,
			'description' => "BLOCK USER! ".$message,
		];
		Logs::addLog($logs);
		return $this->redirect(['index']);
	}
	
	protected function findModel($id)
	{
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionType()
	{
		$searchModel = new UserTypeSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('type', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}
	
	public function actionTypeCreate()
	{
		$message = "Create Type User not validate.";
		$model = new MasterCode();
        if ($model->load(Yii::$app->request->post())) {
			$connection = \Yii::$app->db;
			$transaction = $connection->beginTransaction();
			try{
				$auth = \Yii::$app->authManager;
				$author = $auth->createRole($model->type."-".$model->code);
				if($model->save() && $auth->add($author)){
					$logs=	[
						'type' => Logs::TYPE_USER,
						'description' => "{type: ".$model->type.", code: ".$model->code."}. Create Type User SUKSES",
					];
					Logs::addLog($logs);
					$transaction->commit();
					Yii::$app->session->setFlash('success', 'Create Type User Success');
					return $this->redirect(['type']); 
				}else{
					$message = '';
					foreach($model->errors as $error=>$value){
						$message = $value[0].', ';
					}
					$message = substr($message,0,-2);
					Yii::$app->session->setFlash('success', $message);
				}
			}catch (\Exception $e) {
				$message = $e->getMessage();
				Yii::$app->session->setFlash('error', $e->getMessage());
				$transaction->rollBack();
			}
			$logs=	[
				'type' => Logs::TYPE_USER,
				'description' => "{type: ".$model->type.", code: ".$model->code."}. Create Type User ERROR. Message: ".$message,
			];
			Logs::addLog($logs);
        }
        return $this->render('type_create', [ 
            'model' => $model, 
        ]); 
	}
	
	public function actionTypeUpdate($type, $code)
	{
		$message = "Update Type User not validate.";
		
		$model = MasterCode::find()->where(['type'=>$type, 'code'=>$code])->andWhere("type IN ('HO', 'DISTRIBUTOR')")->one();
		if(isset($model)){
			if($model->load(Yii::$app->request->post())){
				$connection = \Yii::$app->db;
				$transaction = $connection->beginTransaction();
				try{								
					if($model->save()){
						$cek = AuthItem::find()->where(['name'=>$type."-".$code])->one();
						if(isset($cek)){							
							AuthItem::updateAll(['name'=>$model->type."-".$model->code], 'name="'.$type."-".$code.'"');
							AuthAssignment::updateAll(['item_name'=>$model->type."-".$model->code], 'item_name="'.$type."-".$code.'"');
							AuthItemChild::updateAll(['parent'=>$model->type."-".$model->code], 'parent="'.$type."-".$code.'"');
							AuthItemChild::updateAll(['child'=>$model->type."-".$model->code], 'child="'.$type."-".$code.'"');
						}else{
							$auth = \Yii::$app->authManager;
							$author = $auth->createRole($type."-".$code);
							$auth->add($author);
						}
						
						$logs=	[
							'type' => Logs::TYPE_USER,
							'description' => "{type: ".$model->type.", code: ".$model->code."}. Update Type User SUKSES",
						];
						Logs::addLog($logs);
						$transaction->commit();
						Yii::$app->session->setFlash('success', 'Update Type User Success');
						return $this->redirect(['type']); 
					}
				}catch (\Exception $e) {
					$message = $e->getMessage();
					Yii::$app->session->setFlash('error', $e->getMessage());
					$transaction->rollBack();
				}
				
				$logs=	[
					'type' => Logs::TYPE_USER,
					'description' => "{type: ".$model->type.", code: ".$model->code."}. Update Type User ERROR. Message: ".$message,
				];
				Logs::addLog($logs);
			}
			
			return $this->render('type_update', [			
				'model' => $model,
			]);
		}else{
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
	
	public function actionDelete($type, $code)
	{
		$message = 'Delete Type User not validate.';
		$success = false;
		$model = MasterCode::find()
            ->where(['type'=>$type, 'code'=>$code])
            ->one();
		if(isset($model)){
			$jml_user = Profile::find()->where(['typeuser_id'=>$model->id])->count();
			if($jml_user > 0){
				$message = "Type User Masih digunakan di ".$jml_user." User";
			}else{
				$connection = \Yii::$app->db;
				$transaction = $connection->beginTransaction();
				try{					
					if($model->delete()){
						$cruda = $this->cruda();
						foreach($cruda as $ind=>$value){							
							AuthItemChild::deleteAll("parent = '".$model->type."-".$model->code."' OR child = '".$model->type."-".$model->code."'");
							AuthAssignment::deleteAll(['item_name'=>$model->type."-".$model->code]);
							AuthItem::deleteAll(['name'=>$model->type."-".$model->code]);
						}
						$transaction->commit();
						
						$message = "Delete Type User ".$code;
						$success = true;
					}else{
						$message = "Delete Type User failed.";
					}
				}catch (\Exception $e) {
					$message = $e->getMessage();
					$transaction->rollBack();
				}
			}
			Yii::$app->session->setFlash((($success) ? 'success' : 'error'), $message);			
			$logs=	[
				'type' => Logs::TYPE_USER,
				'description' => "{type: ".$model->type.", code: ".$model->code."}. Delete Type User ".(($success) ? 'SUKSES' : 'ERROR').". ".$message,
			];
			Logs::addLog($logs);
				
			return $this->redirect(['type']); 
		}else{
			throw new NotFoundHttpException('The requested page does not exist.');
		}		
	}
	
	private function createReadUpdateDeleteApproval()
	{
		return [
            'C'=>'CREATE',
            'R'=>'READ',
            'U'=>'UPDATE',
            'D'=>'DELETE',
            'A'=>'APPROVAL'
        ];
	}
}