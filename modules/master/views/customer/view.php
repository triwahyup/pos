<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterPerson */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Customer', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-person-view">
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
                'address',
                [
                    'attribute' => 'provinsi_id',
                    'value'=> function ($model, $index) { 
                        return (isset($model->provinsi)) ? $model->provinsi->name : '-';
                    }
                ],
                [
                    'attribute' => 'kabupaten_id',
                    'value'=> function ($model, $index) { 
                        return (isset($model->kabupaten)) ? $model->kabupaten->name : '-';
                    }
                ],
                [
                    'attribute' => 'kecamatan_id',
                    'value'=> function ($model, $index) { 
                        return (isset($model->kecamatan)) ? $model->kecamatan->name : '-';
                    }
                ],
                [
                    'attribute' => 'kelurahan_id',
                    'value'=> function ($model, $index) { 
                        return (isset($model->kelurahan)) ? $model->kelurahan->name : '-';
                    }
                ],
                'kode_pos',
                'phone_1',
                'phone_2',
                'email:email',
                'fax',
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
</div>