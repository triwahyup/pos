<?php
use app\commands\Helper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\master\models\MasterMaterialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Material Item';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-material-index">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create Material Item</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'type_code',
                'value' => function($model, $index, $key){
                    return (isset($model->typeCode)) ? $model->typeCode->name : '';
                }
            ],
            [
                'attribute' => 'material_code',
                'value' => function($model, $index, $key){
                    return (isset($model->material)) ? $model->material->name : '';
                }
            ],
            [
                'attribute' => 'satuan_code',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'value' => function($model, $index, $key){
                    return (isset($model->satuan)) ? $model->satuan->name : '';
                }
            ],
            [
                'attribute' => 'group_material_code',
                'value' => function($model, $index, $key){
                    return (isset($model->groupMaterial)) ? $model->groupMaterial->name : '';
                }
            ],
            [
                'attribute' => 'group_supplier_code',
                'value' => function($model, $index, $key){
                    return (isset($model->groupSupplier)) ? $model->groupSupplier->name : '';
                }
            ],
            [
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['eye-open'],
                            ['view', 'code'=>$model->code],
                            ['title'=>'View', 'aria-label'=>'View', 'data-pjax'=>true]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['pencil'],
                            ['update', 'code'=>$model->code],
                            ['title'=>'Update', 'aria-label'=>'Update', 'data-pjax'=>true]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Helper::buttonIcons()['trash'],
                            ['delete', 'code'=>$model->code],
                            [
                                'title'=>'Delete',
                                'aria-label'=>'Delete', 
                                'data-pjax'=>true,
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]);
                    },
                ],
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'text-center column-action',
                ],
                'template' => '{view} {update} {delete}',
            ],
        ],
    ]); ?>
</div>