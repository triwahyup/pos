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
                            <th class="text-center">Bahan</th>
                            <th class="text-center">PxL</th>
                            <th class="text-center">Potong / Objek</th>
                            <th class="text-center">Mesin / Jml. Warna</th>
                            <th class="text-center">Harga (Ct/Lb)</th>
                            <th class="text-center">Lb. Ikat</th>
                            <th class="text-center">Min. Order (Ct/Lb)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($model->details) > 0): ?>
                            <?php foreach($model->details as $index=>$val): ?>
                                <tr>
                                    <td class="text-center"><?=$index+1?></td>
                                    <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                    <td class="text-center"><?=$val->panjang.'x'.$val->lebar ?></td>
                                    <td class="text-center"><?=$val->potong.'/'.$val->objek ?></td>
                                    <td class="text-center"><?=$val->mesin.'/'.$val->jumlah_warna ?></td>
                                    <td class="text-right"><?='Rp.'.number_format($val->harga_jual) .'.- / Rp. '.number_format($val->harga_cetak).'.-' ?></td>
                                    <td class="text-center"><?=$val->lembar_ikat ?></td>
                                    <td class="text-center"><?=$val->min_order_ct.'/'.$val->min_order_lb ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td class="text-center text-danger" colspan="5">Data is empty</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </fieldset>
    </div>
</div>