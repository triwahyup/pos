<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseOrder */

$this->title = 'No. PO: '.$model->no_po;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-order-view">
    <p class="text-right">
        <?php if($typeuser == 'ADMINISTRATOR' || $typeuser == 'ADMIN'): ?>
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_po' => $model->no_po], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
            <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'no_po' => $model->no_po], [
                    'class' => 'btn btn-danger btn-flat btn-sm',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
        <?php endif; ?>
    </p>

    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 pading-right-0">
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 pading-right-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'no_po',
                    'tgl_po',
                    'tgl_kirim',
                    [
                        'attribute' => 'term_in',
                        'value' => function($model, $value) {
                            return $model->term_in .' Hari';
                        }
                    ],
                    [
                        'attribute' => 'supplier_code',
                        'value' => function($model, $value) {
                            return (isset($model->supplier)) ? $model->supplier->name : '';
                        }
                    ],
                    [
                        'attribute' => 'total_order',
                        'value' => function($model, $value) {
                            return number_format($model->total_order).'.-';
                        }
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => function($model, $value) {
                            return (isset($model->profile)) ? $model->profile->name : '';
                        }
                    ],
                ],
            ]) ?>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0 pading-right-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'keterangan:ntext',
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
                    [
                        'attribute' => 'status_terima',
                        'format' => 'raw',
                        'value' => function ($model, $index) { 
                            return $model->statusTerima;
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
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="margin-top-20"></div>
        <fieldset class="fieldset-box">
            <legend>Detail Item</legend>
            <div class="margin-top-20"></div>
            <div class="col-lg-12 col-md-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <table class="table table-bordered table-custom">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Item</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">QTY</th>
                                <th class="text-center">Harga Beli</th>
                                <th class="text-center">Ppn (%)</th>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($model->details) > 0): 
                                $totalOrder=0; ?>
                                <?php foreach($model->details as $index=>$val): 
                                    $totalOrder += $val->total_order; ?>
                                    <tr>
                                        <td class="text-center"><?=$index+1?></td>
                                        <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                        <td class="text-center"><?=$val->satuan ?></td>
                                        <td class="text-right"><?=number_format($val->qty_order) ?></td>
                                        <td class="text-right"><?=number_format($val->harga_beli).'.-' ?></td>
                                        <td class="text-right"><?=(!empty($val->ppn)) ? $val->ppn.'%' : '' ?></td>
                                        <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="text-right" colspan="6"><strong>Total Order:</strong></td>
                                    <td class="text-right"><strong><?=number_format($totalOrder).'.-' ?></strong></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td class="text-center text-danger" colspan="10">Data is empty</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>
        <div class="margin-bottom-20"></div>
    </div>
    <?php if(count($model->approvals) > 0): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
            <div class="margin-top-20"></div>
            <fieldset class="fieldset-box">
                <legend>Detail Approval</legend>
                <div class="margin-top-20"></div>
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <table class="table table-bordered table-custom">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">Tgl. Approve</th>
                                    <th class="text-center">Urutan</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Type User</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($model->approvals as $index=>$approval): ?>
                                    <tr>
                                        <td class="text-center"><?=$index+1 ?></td>
                                        <td class="text-center"><?=($approval->status == 3 || $approval->status == 4) ? date('Y-m-d', $approval->updated_at) : '-' ?></td>
                                        <td class="text-center"><?=$approval->urutan ?></td>
                                        <td><?=(!empty($approval->user_id)) ? (isset($approval->name)) ? $approval->name->name : '' : '' ?></td>
                                        <td><?=(!empty($approval->typeuser_code)) ? (isset($approval->typeUser)) ? $approval->typeUser->name : '' : '' ?></td>
                                        <td class="text-center"><?=$approval->statusApproval ?></td>
                                        <td><?=(!empty($approval->comment)) ? '<span class="font-size-10"><strong>Comment:</strong> '.$approval->comment.'</span>' : '' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>
            <div class="margin-bottom-20"></div>
        </div>
    <?php endif; ?>
    <?php if($sendApproval): ?>
        <div class="text-right">
            <?= Html::a('<i class="fontello icon-paper-plane-1"></i><span>Send Approval</span>', ['send-approval', 'no_po'=>$model->no_po], ['class' => 'btn btn-info btn-flat btn-sm']) ?>
        </div>
    <?php endif; ?>
    <?php if($postInvoice): ?>
        <div class="text-right">
            <?= Html::a('<i class="fontello icon-ok"></i><span>Post to Invoice Order</span>', ['post', 'no_po'=>$model->no_po], ['class' => 'btn btn-info btn-flat btn-sm']) ?>
        </div>
    <?php endif; ?>
    <?php if($typeApproval): ?>
        <div class="text-right">
            <button data-button="popup_app" class="btn btn-success" data-code="<?=$model->no_po ?>" data-type="APPROVE">
                <i class="fontello icon-ok"></i>
                <span>Approve</span>
            </button>
            <button data-button="popup_rjc" class="btn btn-danger" data-code="<?=$model->no_po ?>" data-type="REJECT">
                <i class="fontello icon-reply"></i>
                <span>Reject</span>
            </button>
        </div>
        <div data-form="approval"></div>
    <?php endif; ?>
</div>
<script>
function popup_approval(el)
{
    var data = el.data();
    console.log(data);
    $.ajax({
        type: "POST",
        url: "<?= Url::to(['purchase-order/popup']) ?>",
        data: {
            no_po: data.code,
            type: data.type
        },
        dataType: "text",
		error: function(xhr, status, error) {
            console.log(xhr, status, error);
        },
		beforeSend: function(){
			el.loader("load");
		},
		success: function(data){
            $("[data-form=\"approval\"]").html(data);
        },
		complete: function(){
			el.loader("destroy");
		}
    });
}

function approval()
{
	$.ajax({
		url: "<?= Url::to(['purchase-order/approval']) ?>",
		type: "POST",
		data: $("#form").serialize(),
		dataType: "text",
		error: function(xhr, status, error) {},
		beforeSend: function(){},
		success: function(data){},
		complete: function(){}
	});
}

$(document).ready(function(){
    $("body").off("click","[data-button=\"popup_app\"]").on("click","[data-button=\"popup_app\"]", function(e){
        e.preventDefault();
        popup_approval($(this));
    });
    $("body").off("click","[data-button=\"popup_rjc\"]").on("click","[data-button=\"popup_rjc\"]", function(e){
        e.preventDefault();
        popup_approval($(this));
    });

    $("body").off("click","[data-button=\"approve\"]").on("click","[data-button=\"approve\"]", function(e){
		e.preventDefault();
		approval();
	});
    $("body").off("click","[data-button=\"reject\"]").on("click","[data-button=\"reject\"]", function(e){
		e.preventDefault();
		approval();
	});
});
</script>