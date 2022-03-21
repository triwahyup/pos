<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InventoryBast */

$this->title = 'BAST/'.$model->code;
$this->params['breadcrumbs'][] = ['label' => 'Inventory Bast', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="inventory-bast-view">
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
                [
                    'attribute' => 'date',
                    'value' => function($model, $value) {
                        return date('d-m-Y', strtotime($model->date));
                    }
                ],
                [
                    'attribute' => 'user_id',
                    'value' => function($model, $value) {
                        return (isset($model->profile)) ? $model->profile->name : '';
                    }
                ],
                [
                    'attribute' => 'type_code',
                    'value' => function($model, $value) {
                        return (isset($model->type)) ? $model->type->name : '';
                    }
                ],
                'keterangan',
                [
                    'attribute' => 'post',
                    'format' => 'raw',
                    'value' => function ($model, $index) { 
                        return $model->statusPost;
                    }
                ],
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
    <!-- DETAIL -->
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="margin-top-30"></div>
        <h6>Detail Barang</h6>
        <hr>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <table class="table table-bordered table-custom" data-table="detail">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">QTY</th>
                    <th class="text-center">Satuan</th>
                    <th class="text-center">Kode Serial</th>
                    <th class="text-center">Kode Unik</th>
                    <th class="text-center">Supplier</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($model->details) > 0):  ?>
                    <?php foreach($model->details as $index=>$val):  ?>
                        <tr>
                            <td class="text-center"><?=$index+1?></td>
                            <td><?=$val->name ?></td>
                            <td class="text-right"><?=number_format($val->qty) ?></td>
                            <td class="text-center"><?=$val->um ?></td>
                            <td class="text-center"><?=(!empty($val->kode_sn)) ? $val->kode_sn : '-' ?></td>
                            <td class="text-center"><?=(!empty($val->kode_unik)) ? $val->kode_unik : '-' ?></td>
                            <td><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td class="text-center text-danger" colspan="10">Data is empty</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- /DETAIL -->
    <?php if($model->post == 0): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="text-right">
                <?= Html::a('<i class="fontello icon-ok"></i><span>Terima Bast</span>', ['post', 'code'=>$model->code], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
            </div>
        </div>
    <?php endif; ?>
</div>