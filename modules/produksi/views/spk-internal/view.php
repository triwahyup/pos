<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkInternal */

$this->title = $model->no_spk;
$this->params['breadcrumbs'][] = ['label' => 'Spk Internal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="spk-internal-view">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_spk' => $model->no_spk], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'no_spk' => $model->no_spk], [
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
            'no_spk',
            'tgl_spk',
            'no_so',
            'tgl_so',
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