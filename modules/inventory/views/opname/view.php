<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InventoryOpname */

$this->title = 'Kode Opname: '.$model->code;
$this->params['breadcrumbs'][] = ['label' => 'Inventory Opnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="inventory-opname-view">
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
                'date',
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
            <div class="document-header">Kode Opname: <?=$model->code ?></div>
            <div class="document-body">
                <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                    <h6>Detail Material</h6>
                    <hr />
                    <table class="table table-bordered table-custom">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Item</th>
                                <th class="text-center" colspan="2">QTY Stock</th>
                                <th class="text-center" colspan="2">QTY Opname</th>
                                <th class="text-center">Selisih</th>
                                <th class="text-center">Balance</th>
                                <th class="text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($model->details) > 0): ?>
                                <?php foreach($model->details as $index=>$val): ?>
                                    <tr>
                                        <td class="text-center"><?=$index+1?></td>
                                        <td class="font-size-10"><?=(isset($val->item)) ? '<span class="text-success">'.$val->item->code .'</span><br />'. $val->item->name : '' ?></td>
                                        <?php for($a=1;$a<3;$a++): ?>
                                            <td class="text-right"><?=(!empty($val['qty_stock_'.$a])) ? number_format($val['qty_stock_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : '-' ?></td>
                                        <?php endfor; ?>
                                        <?php for($a=1;$a<3;$a++): ?>
                                            <td class="text-right"><?=(!empty($val['qty_'.$a])) ? number_format($val['qty_'.$a]).'<br /><span class="text-muted font-size-10">'.$val['um_'.$a].'</span>' : null ?></td>
                                        <?php endfor; ?>
                                        <td class="text-right"><?=number_format($val->selisih) .'<br /><span class="text-muted font-size-10">PLANO</span>'?></td>
                                        <td class="text-center"><?=$val->statusBalance ?></td>
                                        <td><?=(!empty($val->keterangan)) ? '<span class="text-muted font-size-10">Keterangan: '.$val->keterangan.'</span>' : '' ?></td>
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
                        <?= Html::a('<i class="fontello icon-paper-plane-1"></i><span>Send Approval</span>', ['send-approval', 'code'=>$model->code], ['class' => 'btn btn-info btn-flat btn-sm']) ?>
                    </div>
                <?php endif; ?>
                <?php if($post): ?>
                    <div class="text-right">
                        <?= Html::a('<i class="fontello icon-ok"></i><span>Post to Stock Gudang</span>', ['post', 'code'=>$model->code], ['class' => 'btn btn-info btn-flat btn-sm']) ?>
                    </div>
                <?php endif; ?>
                <?php if($typeApproval): ?>
                    <div class="text-right">
                        <button data-button="popup_approval" class="btn btn-success" data-code="<?=$model->code ?>" data-type="APPROVE">
                            <i class="fontello icon-ok"></i>
                            <span>Approve</span>
                        </button>
                        <button data-button="popup_reject" class="btn btn-danger" data-code="<?=$model->code ?>" data-type="REJECT">
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
        url: "<?= Url::to(['opname/popup']) ?>",
        data: {
            code: data.code,
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
		url: "<?= Url::to(['opname/approval']) ?>",
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