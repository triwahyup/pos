<?php
use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterBarang */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Barang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-barang-view">
    <p class="text-right">
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-barang[C]')): ?>
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], [
                'class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-barang[U]')): ?>
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'code' => $model->code], [
                'class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-barang[D]')): ?>
            <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', [
                'delete', 'code' => $model->code], ['class' => 'btn btn-danger btn-flat btn-sm', 'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'name',
            [
                'attribute' => 'satuan_code',
                'value' => function ($model, $index) { 
                    return (isset($model->satuan)) ? $model->satuan->name : '';
                }
            ],
            'keterangan:ntext',
            [
                'attribute' => 'status',
                'value'=> function ($model, $index) { 
                    return ($model->status == 1) ? 'Active' : 'Delete';
                }
            ],
            [
                'attribute'=>'created_at',
                'value'=> function ($model, $index) { 
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