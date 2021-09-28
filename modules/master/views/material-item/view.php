<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMaterialItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Material Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-material-item-view">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'code' => $model->code], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'code' => $model->code], [
            'class' => 'btn btn-danger btn-flat btn-sm',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 pading-right-0">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'code',
                'name',
                [
                    'attribute' => 'type_code',
                    'value' => function ($model, $index) { 
                        return (isset($model->typeCode)) ? $model->typeCode->name : '';
                    }
                ],
                [
                    'attribute' => 'material_code',
                    'value' => function ($model, $index) { 
                        return (isset($model->material)) ? $model->material->name : '';
                    }
                ],
                [
                    'attribute' => 'satuan_code',
                    'value' => function ($model, $index) { 
                        return (isset($model->satuan)) ? $model->satuan->name : '';
                    }
                ],
                [
                    'attribute' => 'group_material_code',
                    'value' => function ($model, $index) { 
                        return (isset($model->groupMaterial)) ? $model->groupMaterial->name : '';
                    }
                ],
                [
                    'attribute' => 'group_supplier_code',
                    'value' => function ($model, $index) { 
                        return (isset($model->groupSupplier)) ? $model->groupSupplier->name : '';
                    }
                ],
                'panjang',
                'lebar',
                'gram',
                [
                    'attribute' => 'harga_beli_1',
                    'value' => function ($model, $index) { 
                        return 'Rp.'.number_format($model->harga_beli_1).'.-';
                    }
                ],
                [
                    'attribute' => 'harga_beli_2',
                    'value' => function ($model, $index) { 
                        return 'Rp.'.number_format($model->harga_beli_2).'.-';
                    }
                ],
                [
                    'attribute' => 'harga_beli_3',
                    'value' => function ($model, $index) { 
                        return 'Rp.'.number_format($model->harga_beli_3).'.-';
                    }
                ],
                [
                    'attribute' => 'harga_jual_1',
                    'value' => function ($model, $index) { 
                        return 'Rp.'.number_format($model->harga_jual_1).'.-';
                    }
                ],
                [
                    'attribute' => 'harga_beli_2',
                    'value' => function ($model, $index) { 
                        return 'Rp.'.number_format($model->harga_beli_2).'.-';
                    }
                ],
                [
                    'attribute' => 'harga_beli_3',
                    'value' => function ($model, $index) { 
                        return 'Rp.'.number_format($model->harga_beli_3).'.-';
                    }
                ],
                'keterangan:ntext',
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
</div>