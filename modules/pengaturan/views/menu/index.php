<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pengaturan\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menu';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Menu</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'link',
            [
                'attribute' => 'type',
                'label' => 'Position',
                'value' => function ($model, $key, $index) {
                    return $model->position();
                }
            ],
            'parent_id',
            [
                'attribute' => 'level',
                'contentOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'attribute' => 'urutan',
                'contentOptions' => [
                    'class' => 'text-center'
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'text-center column-action'
                ],
            ],
        ],
    ]); ?>
</div>