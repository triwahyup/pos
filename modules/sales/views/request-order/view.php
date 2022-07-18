<?php
use app\models\User;
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
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[U]')): ?>
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_request' => $model->no_request], [
                'class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[D]')): ?>
            <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'no_request' => $model->no_request], [
                'class' => 'btn btn-danger btn-flat btn-sm', 'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>
    <div class="form-container no-background" render="detail">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <h6>Detail Request</h6>
            <hr />
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>No. Request</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->no_request ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Tgl. Request</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=date('d-m-Y', strtotime($model->tgl_request)) ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Keterangan</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->keterangan ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Post</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->statusPost ?></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <label>Status Approval</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-right-0">
                    <span><?=$model->statusApproval ?></span>
                </div>
            </div>
        </div>
        <!-- detail item -->
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h6>Detail Item</h6>
            <hr class="margin-top-0" />
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center">Qty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->itemsMaterial) > 0): ?>
                        <?php foreach($model->itemsMaterial as $no=>$val): ?>
                            <tr>
                                <td class="text-center"><?=$no+1 ?></td>
                                <td class="text-center"><?=$val->item_code ?></td>
                                <td><?=(isset($val->item)) ? $val->item->name : '-' ?></td>
                                <td><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></td>
                                <td class="text-right">
                                    <?=(!empty($val->qty_order_1)) 
                                        ? number_format($val->qty_order_1).' '.$val->um_1 
                                        : number_format($val->qty_order_2).' '.$val->um_2 
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td class="text-center text-danger" colspan="10"><i>Data is empty ...</i></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- /detail item -->
        <!-- detail bahan pembantu -->
        <div class="col-lg-12 col-md-12 col-xs-12 margin-top-20">
            <h6>Detail Bahan Pembantu</h6>
            <hr class="margin-top-0" />
        </div>
        <div class="col-lg-12 col-md-12 col-xs-12">
            <table class="table table-bordered table-custom">
                <thead>
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Supplier</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Jenis</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($model->itemsNonMaterial) > 0): ?>
                        <?php foreach($model->itemsNonMaterial as $no=>$val): ?>
                            <tr>
                                <td class="text-center"><?=$no+1 ?></td>
                                <td class="text-center"><?=$val->item_code ?></td>
                                <td><?=(isset($val->item)) ? $val->item->name : '-' ?></td>
                                <td><?=(isset($val->supplier)) ? $val->supplier->name : '-' ?></td>
                                <td class="text-center"><?=$val->qty_order_1 .' '.$val->um_1 ?></td>
                                <td class="text-center"><?=$val->item->material->name ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="text-danger" colspan="8">Data tidak ditemukan</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- /detail bahan pembantu -->
        <!-- detail approval -->
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
        <!-- /detail approval -->
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 text-right padding-right-0">
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[R]')): ?>
            <?php if(\Yii::$app->user->identity->profile->typeUser->value == 'ADMINISTRATOR' || \Yii::$app->user->identity->profile->typeUser->value == 'OWNER'): ?>
                <?= Html::a('<i class="fontello icon-list"></i><span>Invoice Request Order</span>', ['invoice', 'no_request'=>$model->no_request], ['class' => 'btn btn-warning btn-flat btn-sm', 'target'=>'_blank']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[A]')): ?>
            <?php if($typeApproval): ?>
                <button data-button="popup_approval" class="btn btn-success" data-request="<?=$model->no_request ?>" data-type="APPROVE">
                    <i class="fontello icon-ok"></i>
                    <span>Approve</span>
                </button>
                <button data-button="popup_reject" class="btn btn-danger" data-request="<?=$model->no_request ?>" data-type="REJECT">
                    <i class="fontello icon-reply"></i>
                    <span>Reject</span>
                </button>
                <div data-form="approval"></div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-material-sales-order[U]')): ?>
            <?php if($sendApproval): ?>
                <?= Html::a('<i class="fontello icon-paper-plane-1"></i><span>Send Approval</span>', ['send-approval', 'no_request'=>$model->no_request], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
            <?php endif; ?>
            <?php if($postSpk): ?>
                <?= Html::a('<i class="fontello icon-ok"></i><span>Post to SPK</span>', ['post', 'no_request'=>$model->no_request], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
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