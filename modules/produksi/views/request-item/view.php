<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkRequestItem */

$this->title = 'No. Request: '. $model->no_request;
$this->params['breadcrumbs'][] = ['label' => 'Request Item', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="spk-request-item-view">
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

    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 pading-right-0">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'no_request',
                'tgl_request',
                'no_spk',
                'keterangan',
                [
                    'attribute' => 'status_approval',
                    'format' => 'raw',
                    'value'=> function ($model, $index) { 
                        return $model->statusApproval;
                    }
                ],
                [
                    'attribute' => 'post',
                    'format' => 'raw',
                    'value'=> function ($model, $index) { 
                        return $model->statusPost;
                    }
                ],
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
    
    <?php if(count($model->details) > 0): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 margin-top-40">
            <h6>Detail Material</h6>
            <hr />
        </div>
        <?php foreach($model->details as $index=>$val): ?>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0" data-form="detail">
                <!-- Detail Material -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="font-size-12">Material</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                            <span class="font-size-12"><?=$val->item_code.' - '.$val->item->name ?></span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="font-size-12">QTY Order</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                            <strong class="font-size-12">
                                <?php for($a=1;$a<3;$a++): ?>
                                    <?=(!empty($val['qty_order_'.$a])) ? number_format($val['qty_order_'.$a]).' '.$val['um_'.$a] : null ?>
                                <?php endfor; ?>
                            </strong>
                            <span class="text-muted font-size-12">
                                <?='('.$val->stock->satuanTerkecil($val->item_code, [0=>$val->qty_order_1, 1=>$val->qty_order_2]).' LEMBAR)' ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="font-size-12">Harga Jual (Rp)</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                            <strong class="font-size-12">
                                <?php for($a=1;$a<3;$a++): ?>
                                    <?=(!empty($val['harga_jual_'.$a])) ? 
                                        'Rp.'.number_format($val['harga_jual_'.$a]).'.-
                                        <span class="text-muted font-size-10">(Per '.$val['um_'.$a].')</span><br />' 
                                        : null ?>
                                <?php endfor; ?>
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="font-size-12">P x L</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                            <strong class="font-size-12"><?=$val->panjang.' x '.$val->lebar ?></strong>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="font-size-12">Total Potong</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                            <strong class="font-size-12"><?=$val->total_potong.' <span class="text-muted font-size-10">(Jumlah cetak '.number_format($val['jumlah_cetak']).')</span>' ?></strong>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="font-size-12">Total Objek</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                            <strong class="font-size-12"><?=$val->total_objek.' <span class="text-muted font-size-10">(Jumlah objek '.number_format($val['jumlah_objek']).')</span>' ?></strong>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <label class="font-size-12">Total Warna / Lb.Ikat</label>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 padding-right-0">
                            <strong class="font-size-12"><?=$val->total_warna.' / '.$val->typeIkat ?></strong>
                        </div>
                    </div>
                </div>
                <!-- /Detail Material -->
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if(count($model->approvals) > 0): ?>
        <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0 margin-top-20">
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
            <?= Html::a('<i class="fontello icon-paper-plane-1"></i><span>Send Approval</span>', ['send-approval', 'no_request'=>$model->no_request], ['class' => 'btn btn-info btn-flat btn-sm']) ?>
        </div>
    <?php endif; ?>
    <?php if($postSpk): ?>
        <div class="text-right">
            <?= Html::a('<i class="fontello icon-ok"></i><span>Post to SPK</span>', ['post', 'no_request'=>$model->no_request], ['class' => 'btn btn-info btn-flat btn-sm']) ?>
        </div>
    <?php endif; ?>
    <?php if($typeApproval): ?>
        <div class="text-right">
            <button data-button="popup_approval" class="btn btn-success" data-code="<?=$model->no_request ?>" data-type="APPROVE">
                <i class="fontello icon-ok"></i>
                <span>Approve</span>
            </button>
            <button data-button="popup_reject" class="btn btn-danger" data-code="<?=$model->no_request ?>" data-type="REJECT">
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
        url: "<?= Url::to(['request-item/popup']) ?>",
        data: {
            no_request: data.code,
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
		url: "<?= Url::to(['request-item/approval']) ?>",
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