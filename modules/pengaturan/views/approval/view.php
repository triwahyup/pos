<?php
use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\pengaturan\models\PengaturanApproval */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan Approval', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pengaturan-approval-view">
    <p class="text-right">
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-approval[C]')): ?>
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], [
                'class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-approval[U]')): ?>
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'code' => $model->code], [
                'class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-approval[D]')): ?>
            <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'code' => $model->code], [
                'class' => 'btn btn-danger btn-flat btn-sm', 'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <div class="col-lg-12 col-md-12 col-xs-12">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                'slug',
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
    <div class="col-lg-12 col-md-12 col-xs-12 margin-top-30">
        <h6>Detail User Approval</h6>
        <hr />
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12">
        <table class="table table-bordered table-custom" data-table="detail">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Level</th>
                    <th class="text-center">User</th>
                    <th class="text-center">Type User</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($model->details) > 0): ?>
                    <?php foreach($model->details as $index=>$val): ?>
                        <tr>
                            <td class="text-center"><?=$index+1?></td>
                            <td class="text-center"><?=$val->urutan ?></td>
                            <td><?=(isset($val->profile)) ? $val->profile->name : '-' ?></td>
                            <td><?=(isset($val->typeUser)) ? $val->typeUser->name : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>