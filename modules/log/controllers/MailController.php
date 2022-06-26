<?php

namespace app\modules\log\controllers;

use app\models\User;
use app\modules\log\models\LogsMail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class MailController extends Controller
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
                            'actions' => ['index', 'load-data'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('log-mail[R]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('log-mail[D]')),
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
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLoadData()
    {
        $page = 1;
		if(!empty($_POST['Filter']['page'])) $page = $_POST['Filter']['page'];
		$limit = 20;
		if(!empty($_POST['Filter']['limit']))	$page = $_POST['Filter']['limit'];
		$offset = ($page - 1) * $limit;

        $sql = "
            SELECT * FROM logs_mail ORDER BY created_at ASC LIMIT ".$limit." OFFSET ".$offset."
        ";
        $model = \Yii::$app->db->createCommand($sql)->queryAll();

        $sql = "
            SELECT COUNT(*) FROM logs_mail
        ";
        $count = \Yii::$app->db->createCommand($sql)->queryScalar();
        $data = $this->renderPartial('load', [
            'model' => $model,
            'count' => $count,
            'paging' => $this->get_paging_info($count, $limit, $page),
            'no' => $offset +1,
        ]);
        return json_encode(['data'=>$data]);
    }

    function get_paging_info($tot_rows, $itemsPerPage, $curr_page)
	{
		$pages = ceil($tot_rows / $itemsPerPage);
		$data = [];
		$data['si'] = ($curr_page * $itemsPerPage) - $itemsPerPage;
		$data['pages'] = $pages;
		$data['curr_page'] = $curr_page;
		return $data;
	}

    public function actionDelete()
    {
        $success = true;
		$message = 'SUCCESS DELETE DATA LOG.';
        $count = LogsMail::find()->count();
        if($count > 0){
            LogsMail::deleteAll();
            $connection = \Yii::$app->db;
            $connection->createCommand('ALTER TABLE logs_mail AUTO_INCREMENT=1')->query();
        }else{
            $success = false;
            $message = 'DATA LOG MASIH KOSONG.';
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }
}
