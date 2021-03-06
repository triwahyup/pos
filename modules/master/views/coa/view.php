<?php
use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterCoa */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Master Coa', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-coa-view">
    <p class="text-right">
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-coa[C]')): ?>
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], [
                'class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-coa[U]')): ?>
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'code' => $model->code], [
                'class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('data-coa[D]')): ?>
            <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'code' => $model->code], [
                'class' => 'btn btn-danger btn-flat btn-sm', 'data' => [
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
                'code',
                'name',
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
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 margin-top-30">
        <table class="table table-bordered table-custom" data-table="detail">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Kode</th>
                    <th class="text-center">Name</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($model->details) > 0): ?>
                    <?php foreach($model->details as $index=>$val): ?>
                        <tr>
                            <td class="text-center"><?=$index +1 ?></td>
                            <td class="text-center"><?=$val->urutan ?></td>
                            <td><?=$val->name ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-center text-danger" colspan="5">Data is empty</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>