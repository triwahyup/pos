<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterOrder */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-order-view">
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
                'keterangan',
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
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="margin-top-20"></div>
        <fieldset class="fieldset-box">
            <legend>Data Detail</legend>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <table class="table table-bordered table-custom" data-table="detail">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Item</th>
                            <th class="text-center" colspan="3">QTY</th>
                            <th class="text-center" colspan="3">QTY Detail</th>
                            <th class="text-center">Harga Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($model->details) > 0): ?>
                            <?php foreach($model->details as $index=>$val): ?>
                                <tr>
                                    <td class="text-center"><?=$index+1?></td>
                                    <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                    <?php for($a=1;$a<=3;$a++): ?>
                                        <td class="text-right"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
                                    <?php endfor; ?>
                                    <td class="text-right"><?=number_format($val['jumlah_cetak']).'.- <br /><span class="text-muted font-size-10">QTY Cetak</span>' ?></td>
                                    <td class="text-right"><?=number_format($val['jumlah_objek']).'.- <br /><span class="text-muted font-size-10">QTY Objek</span>' ?></td>
                                    <td class="text-right"><?=number_format($val['jumlah_lem']).'.- <br /><span class="text-muted font-size-10">QTY Lem</span>' ?></td>
                                    <td class="text-right"><?=number_format($val['harga_cetak']).'.- <br /><span class="text-muted font-size-10">Per Objek</span>' ?></td>
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
        </fieldset>
    </div>
</div>