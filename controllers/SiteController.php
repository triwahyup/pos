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
                'only' => ['index', 'register', 'switch', 'logout', 'request-password-reset', 'reset-password'],
                'rules' => [
                    [
                        'actions' => ['index', 'login', 'logout', 'navbar-top', 'navbar-left'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
						'actions' => ['register', 'request-password-reset', 'reset-password'],
						'allow' => true, 
						'roles' => ['?'],
					],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'switch' => ['post'],
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
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

    public function actionRegister()
	{
		if(User::find()->count()){
			return $this->redirect(['login']);
		}else{
			$this->layout = 'main-register';
			
			$superuser = new SuperuserForm;
			$superuser->iserror = 0;
			if($superuser->load( \Yii::$app->request->post())){
				if($superuser->register()){
					$superuser->iserror = 1;
				}
			}
			
			return $this->render('register', [
				'register' => $register,
				'superuser' => $superuser,
			]);
		}
	}

    public function actionSwitch()
	{
		$originalId = \Yii::$app->session->get('user.idbeforeswitch');
		if($originalId) {
			$user = User::findOne($originalId);
			$tmp = User::findOne(Yii::$app->user->id);
			if(isset($user)){
				$duration = 0;
				$logs = [
					'type' => Logs::TYPE_USER,
					'user' => $user->id,
					'description' => 'Back Switch from User "'.$tmp->profile->name.'"',
				];
				
				\Yii::$app->user->switchIdentity($user, $duration);
				\Yii::$app->session->remove('user.idbeforeswitch');
				
				Logs::addLog($logs);
				return $this->goHome();
			}else{
				\Yii::$app->session->setFlash('error', 'User not found !');
			}
		}
		return $this->goBack();
	}

    public function actionRequestPasswordReset()
	{
		$this->layout = 'main-login';
		
        $model = new PasswordResetRequestForm();
        if (!$model->load(\Yii::$app->request->post()) || !$model->validate()) {
            return $this->render('requestPasswordResetToken', ['model' => $model]);
        }
        if (!$model->sendEmail()){
            \Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
        }else{
			\Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
			return $this->refresh();
		}
        return $this->goHome();
    }

    public function actionResetPassword($token)
	{
		$this->layout = 'main-login';
		
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if(!$model->load( \Yii::$app->request->post()) || !$model->validate() || !$model->resetPassword()) {
            return $this->render('resetPassword', ['model' => $model]);
        }
        \Yii::$app->session->setFlash('success', 'New password was saved. Please login to your account');
        return $this->goHome();      
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
            SELECT * FROM pengaturan_menu
            WHERE position=2
            ORDER BY level, urutan")->queryAll();
        
        $data = [];
        foreach($querys as $menu){
            $data[$menu['id']] = $menu['parent_id'];
            if($menu['level'] == 1){
                $menuItems[$menu['id']] = [
                    'urutan' => $menu['urutan'],
					'label' => $menu['name'],
                    'menuId' => $menu['id'],
					'slug' => $menu['slug'],
                    'url' => $menu['link'],
					'parentId' => $menu['parent_id'],
                ];
            }
            else if($menu['level'] == 2){
                if(!empty($menu['parent_id'])){
                    $menuItems[$menu['parent_id']]['items'][$menu['id']] = [
                        'urutan' => $menu['urutan'],
                        'label' => $menu['name'],
                        'menuId' => $menu['id'],
                        'slug' => $menu['slug'],
                        'url' => $menu['link'],
                        'parentId' => $menu['parent_id'],
                    ];
                }
            }
            else if($menu['level'] == 3){
                if(!empty($menu['parent_id'])){
                    if(!empty($data[$menu['parent_id']])){
                        $menuItems[$data[$menu['parent_id']]]['items'][$menu['parent_id']]['items'][$menu['id']] = [
                            'urutan' => $menu['urutan'],
                            'label' => $menu['name'],
                            'menuId' => $menu['id'],
                            'slug' => $menu['slug'],
                            'url' => $menu['link'],
                            'parentId' => $menu['parent_id'],
                        ];
                    }
                }
            }
        }
        $data = $this->renderPartial('navbar-left', ['menuItems' => $menuItems]);
        return json_encode(['data'=>$data]);
    }
}
