<?php
use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkPotongRoll */

$this->title = $model->code;
$this->params['breadcrumbs'][] = ['label' => 'Spk Potong Roll', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="spk-potong-roll-view">
    <p class="text-right">
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-potong-material-roll[C]')): ?>
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], [
                'class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-potong-material-roll[U]')): ?>
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'code' => $model->code], [
                'class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-potong-material-roll[D]')): ?>
            <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'code' => $model->code], [
                'class' => 'btn btn-danger btn-flat btn-sm', 'data' => [
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
                    'code',
                    [
                        'attribute' => 'item_code',
                        'label' => 'Item Name',
                        'value' => function($model, $value) {
                            return (isset($model->item)) ? $model->item->name : '';
                        }
                    ],
                    [
                        'attribute' => 'supplier_code',
                        'value' => function($model, $value) {
                            return (isset($model->supplier)) ? $model->supplier->name : '';
                        }
                    ],
                    [
                        'attribute' => 'type_code',
                        'value' => function($model, $value) {
                            return (isset($model->typeCode)) ? $model->typeCode->name : '';
                        }
                    ],
                    [
                        'attribute' => 'material_code',
                        'value' => function($model, $value) {
                            return (isset($model->material)) ? $model->material->name : '';
                        }
                    ],
                    [
                        'attribute' => 'satuan_code',
                        'value' => function($model, $value) {
                            return (isset($model->satuan)) ? $model->satuan->name : '';
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
    <!-- DETAIL -->
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="margin-top-30"></div>
        <h6>Detail Proses Potong</h6>
        <hr>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <table class="table table-bordered table-custom" data-table="detail">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">New Name</th>
                    <th class="text-center">PxL</th>
                    <th class="text-center">Gram</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Waste</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($model->details) > 0):  ?>
                    <?php foreach($model->details as $index=>$val):  ?>
                        <tr>
                            <td class="text-center"><?=$index+1?></td>
                            <td><?=$val->name ?></td>
                            <td class="text-center"><?=$val->panjang.' x '. $val->lebar ?></td>
                            <td class="text-right"><?=$val->gram .'<span class="text-muted"> Gram</span>' ?></td>
                            <td class="text-right"><?=$val->qty .'<span class="text-muted"> Lembar</span>' ?></td>
                            <td class="text-right"><?=(!empty($val->qty_sisa)) ? $val->qty_sisa : 0 ?></td>
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
    <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('request-potong-material-roll[U]')): ?>
        <?php if($model->post == 0): ?>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <div class="text-right">
                    <?= Html::a('<i class="fontello icon-ok"></i><span>Post to Stock Item</span>', ['post', 'code'=>$model->code], ['class' => 'btn btn-primary btn-flat btn-sm']) ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>