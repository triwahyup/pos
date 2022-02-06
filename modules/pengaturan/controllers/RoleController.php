<?php

namespace app\modules\pengaturan\controllers;

use app\models\AuthAssignment;
use app\models\AuthItemChild;
use app\models\Profile;
use app\models\User;
use app\modules\master\models\MasterKode;
use app\modules\pengaturan\models\PengaturanMenu;
use app\modules\pengaturan\models\RoleForm;
use app\modules\pengaturan\models\RoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * RoleController implements the CRUD actions for MasterKode model.
 */
class RoleController extends Controller
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
                            'actions' => ['index'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('role-menu')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['update'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('role-menu')),
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all MasterKode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($code)
    {
        $typeCode = MasterKode::findOne(['code'=>$code]);
        $parent = str_replace(' ','-', $typeCode->value);
        if($typeCode !== NULL){
            $menus = PengaturanMenu::find()
                ->leftJoin('master_kode', 'master_kode.code = pengaturan_menu.type_code')
                ->where(['level'=>1, 'value'=>\Yii::$app->params['TYPE_MENU_LEFT']])
                ->orderBy(['type_code'=>SORT_DESC, 'urutan'=>SORT_ASC])
                ->all();

            $success = true;
            $message = '';
            $model = new RoleForm();
            if($model->load(\Yii::$app->request->post())){
                $connection = \Yii::$app->db;
				$transaction = $connection->beginTransaction();
                try{
                    if(count($model->menu) > 0){
                        AuthItemChild::deleteAll('parent =:parent', [':parent'=>$parent]);
                        $auth = \Yii::$app->authManager;
                        $authParent = $auth->getRole($parent);
                        if(empty($authParent))
                            $authParent = $auth->createRole($parent);
                        $role = '';
                        foreach($model->menu as $menu){
                            $role .= $menu.', ';
							$getChild = $auth->getRole($menu);
                            if(!$auth->addChild($authParent, $getChild)){
                                $success = false;
								$message = '{code: '.$typeCode->value.'}. Insert Auth Item Child not validate';
                            }
                        }

                        $authItems = AuthItemChild::findAll(['parent'=>$parent]);
                        if(count($authItems) > 0){
                            foreach($authItems as $authItem){
                                $profiles = Profile::findAll(['typeuser_code'=>$typeCode, 'status'=>1]);
                                foreach($profiles as $profile){
                                    $authAssignment = new AuthAssignment();
                                    $authAssignment->item_name = $authItem->child;
                                    $authAssignment->user_id = $profile->user_id;
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
                        }

                        if($success){
							$message = '('.substr($role,0,-2).')';
							\Yii::$app->session->setFlash('success', 'Update Rule SUKSES: '.$message);
							$transaction->commit();
							return $this->redirect(['index']);
						}else{
                            $transaction->rollBack();
                        }
                    }else{
						$success = false;
						$message = "Update Role not validate. Menu is empty !";
						\Yii::$app->session->setFlash('error', $message);
					}
                }catch (\Exception $e){
					$success = false;
					$message = $e->getMessage();
					$transaction->rollBack();
					\Yii::$app->session->setFlash('error', $message);
				}
            }else{
                $model->name = $parent;
				$model->menu = [];
				foreach($typeCode->menu as $role){
                    $model->menu[] = $role->child;
				}
            }

            return $this->render('update', [
                'model' => $model,
                'typeCode' => $typeCode,
                'menus' => $menus,
            ]);
        }else{
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModel($code)
    {
        if (($model = PengaturanMenu::findOne($code)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}