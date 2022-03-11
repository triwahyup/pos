<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\sales\models\RequestOrder */

$this->title = $model->no_request;
$this->params['breadcrumbs'][] = ['label' => 'Request Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="request-order-view">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], ['class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_request' => $model->no_request], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'no_request' => $model->no_request], [
            'class' => 'btn btn-danger btn-flat btn-sm',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'no_request',
                    [
                        'attribute' => 'tgl_request',
                        'value' => function($model, $value) {
                            return date('d-m-Y', strtotime($model->tgl_request));
                        }
                    ],
                    [
                        'attribute' => 'user_id',
                        'value' => function($model, $value) {
                            return (isset($model->profile)) ? $model->profile->name : '';
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
        <div class="col-lg-6 col-md-6 col-xs-12 padding-right-0">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'keterangan',
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
                ],
                ]) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 margin-top-40">
        <h6>Detail Bahan</h6>
        <hr>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        <table class="table table-bordered table-custom margin-top-10" data-table="detail">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">No.</th>
                    <th class="text-center" colspan="2">Item</th>
                    <th class="text-center" colspan="2">QTY</th>
                    <th class="text-center" rowspan="2">Jenis</th>
                </tr>
                <tr>
                    <th class="text-center">Code</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Um 1</th>
                    <th class="text-center">Um 2</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($model->items as $index=>$val): ?>
                    <tr>
                        <td class="text-center"><?=$index+1?></td>
                        <td class="text-center"><?=$val->item_code ?></td>
                        <td><?=$val->item->name ?></td>
                        <?php for($a=1;$a<3;$a++): ?>
                            <td class="text-center"><?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).' '.$val['um_'.$a] : null ?></td>
                        <?php endfor; ?>
                        <td class="text-center"><?=$val->item->material->name ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if(count($model->approvals) > 0): ?>
        <div class="col-lg-12 col-md-12 col-xs-12">
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
                            <td class="text-center"><?=($approval->status == 3 || $approval->status == 4) ? date('d-m-Y', $approval->updated_at) : '-' ?></td>
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
            <?= Html::a('<i class="fontello icon-paper-plane-1"></i><span>Send Approval</span>', ['send-approval', 'no_request'=>$model->no_request], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
        </div>
    <?php endif; ?>
    <?php if($postSpk): ?>
        <div class="text-right">
            <?= Html::a('<i class="fontello icon-ok"></i><span>Post to SPK</span>', ['post', 'no_request'=>$model->no_request], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
        </div>
    <?php endif; ?>
    <?php if($typeApproval): ?>
        <div class="text-right">
            <button data-button="popup_approval" class="btn btn-success" data-request="<?=$model->no_request ?>" data-type="APPROVE">
                <i class="fontello icon-ok"></i>
                <span>Approve</span>
            </button>
            <button data-button="popup_reject" class="btn btn-danger" data-request="<?=$model->no_request ?>" data-type="REJECT">
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
    $.ajax({
        type: "POST",
        url: "<?= Url::to(['request-order/popup']) ?>",
        data: {
            no_request: data.request,
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
		url: "<?= Url::to(['request-order/approval']) ?>",
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