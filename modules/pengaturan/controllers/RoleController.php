<?php

namespace app\modules\pengaturan\controllers;

use app\models\AuthAssignment;
use app\models\AuthItemChild;
use app\models\User;
use app\modules\master\models\MasterKode;
use app\modules\master\models\Profile;
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
        if($typeCode !== NULL){
            $menus = PengaturanMenu::find()
                ->where(['level'=>1, 'position'=>'KODE-002'])
                ->orderBy(['position'=>SORT_DESC, 'urutan'=>SORT_ASC])
                ->all();

            $message = '';
			$success = true;
            $model = new RoleForm();
            if($model->load(\Yii::$app->request->post())){
                $connection = \Yii::$app->db;
				$transaction = $connection->beginTransaction();
                try{
                    if(count($model->menu) > 0){
                        AuthItemChild::deleteAll('parent =:parent', [':parent'=>$code]);
                        $auth = \Yii::$app->authManager;
                        $getParent = $auth->getRole($code);
                        $role = '';
                        foreach($model->menu as $menu){
                            $role .= $menu.', ';
							$getChild = $auth->getRole($menu);
                            if(!$auth->addChild($getParent, $getChild)){
                                $success = false;
								$message = '{code: '.$code.'}. Insert Auth Item Child not validate';
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
                $model->name = $code;
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

    protected function findModel($id)
    {
        if (($model = PengaturanMenu::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}