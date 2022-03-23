<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterMaterial */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Material', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-material-view">
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

    <div class="col-lg-12 col-md-12 col-xs-12">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
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
                'lebar',
                'panjang',
                'gram',
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
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="margin-top-40"></div>
        <h6>Pricelist</h6>
        <hr />
        <table class="table table-bordered table-custom">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Nama</th>
                    <th class="text-center">Supplier</th>
                    <th class="text-center" colspan="3">Harga Beli (Rp)</th>
                    <th class="text-center" colspan="3">Harga Jual (Rp)</th>
                    <th class="text-center">Status Pricelist</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($model->pricelists) > 0): ?>
                    <?php foreach($model->pricelists as $index=>$val): ?>
                        <tr>
                            <td class="text-center"><?=($index +1)?></td>
                            <td><?=$val->name ?></td>
                            <td><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></td>
                            <?php for($a=1;$a<=3;$a++): ?>
                                <td class="text-right"><?=(!empty($val['harga_beli_'.$a])) ? number_format($val['harga_beli_'.$a]).'.- <br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
                            <?php endfor; ?>
                            <?php for($a=1;$a<=3;$a++): ?>
                                <td class="text-right"><?=(!empty($val['harga_jual_'.$a])) ? number_format($val['harga_jual_'.$a]).'.- <br /><span class="text-muted font-size-10">Per '.$val['um_'.$a].'</span>' : null ?></td>
                            <?php endfor; ?>
                            <td class="text-center"><?=$val->statusActive ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td class="text-center text-danger" colspan="15">Data is empty</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>