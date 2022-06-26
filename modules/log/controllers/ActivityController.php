<?php

namespace app\modules\log\controllers;

use app\models\User;
use app\modules\log\models\Logs;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ActivityController extends Controller
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
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('log-activity[R]')),
                            'roles' => ['@'],
                        ], 
                        [
                            'actions' => ['delete'],
                            'allow' => (((new User)->getIsDeveloper()) || \Yii::$app->user->can('log-activity[D]')),
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
            SELECT logs.*, profile.name FROM logs
            LEFT JOIN profile ON profile.user_id = logs.user_id
            ORDER BY created_at ASC LIMIT ".$limit." OFFSET ".$offset."
        ";
        $model = \Yii::$app->db->createCommand($sql)->queryAll();

        $sql = "
            SELECT COUNT(*) FROM logs
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
        $success = false;
		$message = 'SUCCESS DELETE DATA LOG.';
        $count = Logs::find()->count();
        if($count > 0){
            $success = true;
            Logs::deleteAll();
        }else{
            $message = 'DATA LOG MASIH KOSONG.';
        }
        return json_encode(['success'=>$success, 'message'=>$message]);
    }
}
