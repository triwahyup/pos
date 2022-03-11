<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Logs;
use app\models\User;
use app\modules\inventory\models\InventoryOpnameApproval;
use app\modules\purchasing\models\PurchaseInternalApproval;
use app\modules\purchasing\models\PurchaseOrderApproval;
use app\modules\sales\models\RequestOrderApproval;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'logout', 'switch'],
                'rules' => [
                    [
                        'actions' => ['index', 'login', 'logout', 'navbar-top', 'navbar-left', 'switch'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionSwitch()
    {
        $originalId = \Yii::$app->session->get('user.idbeforeswitch');
        if($originalId){
            $userOrigin = User::findOne($originalId);
			$userOld = User::findOne(\Yii::$app->user->id);
            if(isset($userOrigin)){
				$duration = 0;
				$logs = [
					'type' => Logs::TYPE_USER,
					'user' => $userOrigin->id,
					'description' => 'Back Switch from User "'.$userOld->profile->name.'"',
				];
				Logs::addLog($logs);
				
				\Yii::$app->user->switchIdentity($userOrigin, $duration);
				\Yii::$app->session->remove('user.idbeforeswitch');
				return $this->goHome();
			}else{
				\Yii::$app->session->setFlash('error', 'User not found !');
			}
        }
        return $this->goBack();
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $userApproval = false;
        // PURCHASE ORDER
        $purchaseApp = PurchaseOrderApproval::findAll(['status'=>2]);
        $countPurchaseApp=0;
        $listPurchaseApp = '';
        foreach($purchaseApp as $val){
            $user = '';
            if(!empty($val->user_id)){
                if(!empty($val->user->profile)){
                    $user = $val->user->profile->name;
                }
            }
            if(!empty($val->typeuser_code)){
                $user = $val->typeUser->name;
            }
            
            if($val->user_id == \Yii::$app->user->id OR $val->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code){
                $userApproval = true;
                $countPurchaseApp += 1;
                $listPurchaseApp .= '<li><a href="'.\Yii::$app->params['URL'].'/purchasing/purchase-order/view&no_po='.$val->no_po.'">'.$countPurchaseApp.'). Approval PO: '.$val->no_po.'<i>'.$user.'</i></a></li>';
            }else{
                $countPurchaseApp += 1;
                $listPurchaseApp .= '<li><span>'.$countPurchaseApp.'). Approval PO: '.$val->no_po.'<i>'.$user.'</i></span></li>';
            }
        }
        // END PURCHASE ORDER

        // PO INTERNAL
        $poInternalApp = PurchaseInternalApproval::findAll(['status'=>2]);
        $countPoInternalApp=0;
        $listPoInternalApp = '';
        foreach($poInternalApp as $val){
            $user = '';
            if(!empty($val->user_id)){
                if(!empty($val->user->profile)){
                    $user = $val->user->profile->name;
                }
            }
            if(!empty($val->typeuser_code)){
                $user = $val->typeUser->name;
            }
            
            if($val->user_id == \Yii::$app->user->id OR $val->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code){
                $userApproval = true;
                $countPoInternalApp += 1;
                $listPoInternalApp .= '<li><a href="'.\Yii::$app->params['URL'].'/purchasing/purchase-internal/view&no_pi='.$val->no_pi.'">'.$countPoInternalApp.'). Approval PO INTERNAL: '.$val->no_pi.'<i>'.$user.'</i></a></li>';
            }else{
                $countPoInternalApp += 1;
                $listPoInternalApp .= '<li><span>'.$countPoInternalApp.'). Approval PO INTERNAL: '.$val->no_pi.'<i>'.$user.'</i></span></li>';
            }
        }
        // END PO INTERNAL

        // REQUEST ORDER
        $requestOrderApp = RequestOrderApproval::findAll(['status'=>2]);
        $countRequestOrderApp=0;
        $listRequestOrderApp = '';
        foreach($requestOrderApp as $val){
            $user = '';
            if(!empty($val->user_id)){
                if(!empty($val->user->profile)){
                    $user = $val->user->profile->name;
                }
            }
            if(!empty($val->typeuser_code)){
                $user = $val->typeUser->name;
            }
            
            if($val->user_id == \Yii::$app->user->id OR $val->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code){
                $userApproval = true;
                $countRequestOrderApp += 1;
                $listRequestOrderApp .= '<li><a href="'.\Yii::$app->params['URL'].'/sales/request-order/view&no_request='.$val->no_request.'">'.$countRequestOrderApp.'). Approval Request Order: '.$val->no_request.'<i>'.$user.'</i></a></li>';
            }else{
                $countRequestOrderApp += 1;
                $listRequestOrderApp .= '<li><span>'.$countRequestOrderApp.'). Approval Request Order: '.$val->no_request.'<i>'.$user.'</i></span></li>';
            }
        }
        // END REQUEST ORDER
        
        // STOCK OPNAME
        $stockOpnameApp = InventoryOpnameApproval::findAll(['status'=>2]);
        $countStockOpnameApp=0;
        $listStockOpnameApp = '';
        foreach($stockOpnameApp as $val){
            $user = '';
            if(!empty($val->user_id)){
                if(!empty($val->user->profile)){
                    $user = $val->user->profile->name;
                }
            }
            if(!empty($val->typeuser_code)){
                $user = $val->typeUser->name;
            }
            
            if($val->user_id == \Yii::$app->user->id OR $val->typeuser_code == \Yii::$app->user->identity->profile->typeuser_code){
                $userApproval = true;
                $countStockOpnameApp += 1;
                $listStockOpnameApp .= '<li><a href="'.\Yii::$app->params['URL'].'/inventory/opname/view&code='.$val->code.'">'.$countStockOpnameApp.'). Approval Opname: '.$val->code.'<i>'.$user.'</i></a></li>';
            }else{
                $countStockOpnameApp += 1;
                $listStockOpnameApp .= '<li><span>'.$countStockOpnameApp.'). Approval Opname: '.$val->code.'<i>'.$user.'</i></span></li>';
            }
        }
        // END STOCK OPNAME
        
        return $this->render('index', [
            'userApproval' => $userApproval,
            'purchaseApp' => $purchaseApp,
            'countPurchaseApp' => $countPurchaseApp,
            'listPurchaseApp' => $listPurchaseApp,
            'poInternalApp' => $poInternalApp,
            'countPoInternalApp' => $countPoInternalApp,
            'listPoInternalApp' => $listPoInternalApp,
            'requestOrderApp' => $requestOrderApp,
            'listRequestOrderApp' => $listRequestOrderApp,
            'countRequestOrderApp' => $countRequestOrderApp,
            'stockOpnameApp' => $stockOpnameApp,
            'countStockOpnameApp' => $countStockOpnameApp,
            'listStockOpnameApp' => $listStockOpnameApp,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'main-login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if(User::find()->count() > 0){
            $model = new LoginForm();
            if ($model->load( \Yii::$app->request->post())) {
                if($model->login()){
                    $logs = [
						'type' => Logs::TYPE_USER,
						'description' => "{username:".$model->username."}. Login SUKSES",
					];
					Logs::addLog($logs);
                    return $this->goBack();
                }else{
                    $message = 'LOGIN ERROR';
                    foreach($model->errors as $error=>$value){
                        $message .= ', '.$error.':'.$value[0].',';
                    }
                    $message = substr($message, 0, -1);
                    $logs = [
                        'type' => Logs::TYPE_USER,
                        'description' => 'Login dengan username:'.$model->username .' '.$message,
                    ];
                    Logs::addLog($logs);
                }
            }
            return $this->render('login', [
                'model' => $model,
            ]);
        }else{
            return $this->redirect(['register']);
		}
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $logs = [
			'type' => Logs::TYPE_USER,
			'description' => 'User logout',
		];
		Logs::addLog($logs);
        
        \Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionNavbarTop()
    {
        $data = $this->renderPartial('navbar-top');
        return json_encode(['data' => $data]);
    }

    public function actionNavbarLeft()
    {
        $menuItems = [];
        $querys = \Yii::$app->db->createCommand("
            SELECT pengaturan_menu.* FROM pengaturan_menu
            LEFT JOIN master_kode ON master_kode.code = pengaturan_menu.type_code
            WHERE pengaturan_menu.status=1 AND value='".\Yii::$app->params['TYPE_MENU_LEFT']."' 
            ".(( \Yii::$app->user->identity->getIsDeveloper()) ? '' : " AND (
                slug IN (
                    SELECT item_name FROM auth_assignment WHERE user_id = '".\Yii::$app->user->id."'
                )
            )")."
            ORDER BY level, urutan")->queryAll();
        
        $data = [];
        foreach($querys as $menu){
            $data[$menu['code']] = $menu['parent_code'];
            if($menu['level'] == 1){
                $menuItems[$menu['code']] = [
                    'urutan' => $menu['urutan'],
					'label' => $menu['name'],
                    'menuId' => $menu['code'],
					'slug' => $menu['slug'],
                    'url' => $menu['link'],
					'parentId' => $menu['parent_code'],
                ];
            }
            else if($menu['level'] == 2){
                if(!empty($menu['parent_code'])){
                    $menuItems[$menu['parent_code']]['items'][$menu['code']] = [
                        'urutan' => $menu['urutan'],
                        'label' => $menu['name'],
                        'menuId' => $menu['code'],
                        'slug' => $menu['slug'],
                        'url' => $menu['link'],
                        'parentId' => $menu['parent_code'],
                    ];
                }
            }
            else if($menu['level'] == 3){
                if(!empty($menu['parent_code'])){
                    if(!empty($data[$menu['parent_code']])){
                        $menuItems[$data[$menu['parent_code']]]['items'][$menu['parent_code']]['items'][$menu['code']] = [
                            'urutan' => $menu['urutan'],
                            'label' => $menu['name'],
                            'menuId' => $menu['code'],
                            'slug' => $menu['slug'],
                            'url' => $menu['link'],
                            'parentId' => $menu['parent_code'],
                        ];
                    }
                }
            }
        }
        $data = $this->renderPartial('navbar-left', ['menuItems' => $menuItems]);
        return json_encode(['data'=>$data]);
    }
}