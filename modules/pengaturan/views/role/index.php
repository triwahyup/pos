<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pengaturan\models\RoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Role Menu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'menu',
                'format' => 'raw',
                'value' => function ($model, $key, $index) { 
                    $menu = '';
                    foreach($model->authItem as $key=>$val){
                        $menu .= '<span class="label-menu">'.$val.'</span> ';
                    }
                    return $menu;
                },
            ],
            [
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fontello icon-pencil-3"></i>',
                                ['update', 'code'=>$model->code],
                                ['title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true]);
                    },
                ],
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'text-center column-action',
                ],
                'template' => '{update}',
            ],
        ],
    ]); ?>
</div>