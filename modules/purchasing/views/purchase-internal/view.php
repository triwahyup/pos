<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\purchasing\models\PurchaseInternal */

$this->title = 'No. PO Internal: '.$model->no_pi;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Internal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="purchase-internal-view">
    <p class="text-right">
        <?php if($typeuser == 'ADMINISTRATOR' || $typeuser == 'ADMIN'): ?>
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_pi' => $model->no_pi], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
            <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'no_pi' => $model->no_pi], [
                    'class' => 'btn btn-danger btn-flat btn-sm',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
        <?php endif; ?>
    </p>

    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 pading-right-0">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'no_pi',
                'tgl_pi',
                [
                    'attribute' => 'total_order',
                    'value' => function($model, $value) {
                        return number_format($model->total_order).'.-';
                    }
                ],
                [
                    'attribute' => 'user_request',
                    'value' => function($model, $value) {
                        return (isset($model->request)) ? $model->request->name : '';
                    }
                ],
                [
                    'attribute' => 'status_approval',
                    'format' => 'raw',
                    'value' => function ($model, $index) { 
                        return $model->statusApproval;
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
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="margin-top-20"></div>
        <div class="document-container">
            <div class="document-header">No. PO Internal: <?=$model->no_pi ?></div>
            <div class="document-body">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <h6>Detail Barang / Item</h6>
                    <hr />
                    <table class="table table-bordered table-custom">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">QTY</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-center">Harga Beli</th>
                                <th class="text-center">Total (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($model->details) > 0): 
                                $totalOrder=0; ?>
                                <?php foreach($model->details as $index=>$val): 
                                    $totalOrder += $val->total_order; ?>
                                    <tr>
                                        <td class="text-center"><?=$index+1?></td>
                                        <td><?=$val->item_name ?></td>
                                        <td class="text-right"><?=number_format($val->qty) ?></td>
                                        <td class="text-center"><?=$val->um ?></td>
                                        <td class="text-right"><?=number_format($val->harga_beli).'.-' ?></td>
                                        <td class="text-right"><?=number_format($val->total_order).'.-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="summary" colspan="5"><strong>Total Order:</strong></td>
                                    <td class="summary"><strong><?=number_format($totalOrder).'.-' ?></strong></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td class="text-center text-danger" colspan="15">Data is empty</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php if(count($model->approvals) > 0): ?>
                    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="margin-bottom-20"></div>
                        <h6>Detail Approval</h6>
                        <hr />
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
                <?php endif; ?>
                <?php if($sendApproval): ?>
                    <div class="text-right">
                        <?= Html::a('<i class="fontello icon-paper-plane-1"></i><span>Send Approval</span>', ['send-approval', 'no_pi'=>$model->no_pi], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
                    </div>
                <?php endif; ?>
                <?php if($typeApproval): ?>
                    <div class="text-right">
                        <button data-button="popup_approval" class="btn btn-success" data-code="<?=$model->no_pi ?>" data-type="APPROVE">
                            <i class="fontello icon-ok"></i>
                            <span>Approve</span>
                        </button>
                        <button data-button="popup_reject" class="btn btn-danger" data-code="<?=$model->no_pi ?>" data-type="REJECT">
                            <i class="fontello icon-reply"></i>
                            <span>Reject</span>
                        </button>
                    </div>
                    <div data-form="approval"></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
function popup_approval(el)
{
    var data = el.data();
    $.ajax({
        type: "POST",
        url: "<?= Url::to(['purchase-internal/popup']) ?>",
        data: {
            no_pi: data.code,
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
		url: "<?= Url::to(['purchase-internal/approval']) ?>",
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
    $("body").off("click","[data-button=\"popup_approval\"]").on("click","[data-button=\"popup_approval\"]", function(e){
        e.preventDefault();
        popup_approval($(this));
    });
    $("body").off("click","[data-button=\"popup_reject\"]").on("click","[data-button=\"popup_reject\"]", function(e){
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