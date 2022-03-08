<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\RequestOrder */

$this->title = $model->no_request;
$this->params['breadcrumbs'][] = ['label' => 'Request Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="request-order-view">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_request' => $model->no_request], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'no_request' => $model->no_request], [
            'class' => 'btn btn-danger btn-flat btn-sm',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'no_request',
            [
                'attribute' => 'tgl_request',
                'value' => function($model, $value) {
                    return date('d-m-Y', strtotime($model->tgl_request));
                }
            ],
            'no_spk',
            [
                'attribute' => 'user_id',
                'value' => function($model, $value) {
                    return (isset($model->profile)) ? $model->profile->name : '';
                }
            ],
            [
                'attribute' => 'post',
                'format' => 'raw',
                'value' => function ($model, $index) { 
                    return $model->statusPost;
                }
            ],
            [
                'attribute' => 'status_approval',
                'format' => 'raw',
                'value' => function ($model, $index) { 
                    return $model->statusApproval;
                }
            ],
            'keterangan',
            [
                'attribute' => 'status',
                'value' => function ($model, $index) { 
                    return ($model->status == 1) ? 'Active' : 'Delete';
                }
            ],
            [
                'attribute'=>'created_at',
                'value' => function ($model, $index) { 
                    if(!empty($model->created_at))
                    {
                        return date('d-m-Y H:i:s',$model->created_at);
                    }
                }
            ],
            [
                'attribute'=>'updated_at',
                'value'=> function ($model, $index) { 
                    if(!empty($model->updated_at))
                    {
                        return date('d-m-Y H:i:s',$model->updated_at);
                    }
                }
            ],
        ],
    ]) ?>
</div>