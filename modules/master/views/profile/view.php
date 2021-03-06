<?php
use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\Profile */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="profile-view">
    <p class="text-right">
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-profile[C]')): ?>
            <?= Html::a('<i class="fontello icon-plus"></i><span>Create</span>', ['create'], [
                'class' => 'btn btn-success btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-profile[U]')): ?>
            <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'user_id' => $model->user_id], [
                'class' => 'btn btn-warning btn-flat btn-sm']) ?>
        <?php endif; ?>
        <?php if(((new User)->getIsDeveloper()) || \Yii::$app->user->can('setup-profile[D]')): ?>
            <?= Html::a('<i class="fontello icon-trash"></i><span>Delete</span>', ['delete', 'user_id' => $model->user_id], [
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
                'name',
                'nik',
                'nip',
                'npwp',
                [
                    'attribute' => 'tgl_lahir',
                    'value'=> function ($model, $index) {
                        return (!empty($model->tgl_lahir)) ? date('d-m-Y', strtotime($model->tgl_lahir)) : null;
                    }
                ],
                'tempat_lahir',
                'alamat',
                [
                    'attribute' => 'provinsi_id',
                    'value'=> function ($model, $index) { 
                        return (isset($model->provinsi)) ? $model->provinsi->name : '-';
                    }
                ],
                [
                    'attribute' => 'kabupaten_id',
                    'value'=> function ($model, $index) { 
                        return (isset($model->kabupaten)) ? $model->kabupaten->name : '-';
                    }
                ],
                [
                    'attribute' => 'kecamatan_id',
                    'value'=> function ($model, $index) { 
                        return (isset($model->kecamatan)) ? $model->kecamatan->name : '-';
                    }
                ],
                [
                    'attribute' => 'kelurahan_id',
                    'value'=> function ($model, $index) { 
                        return (isset($model->kelurahan)) ? $model->kelurahan->name : '-';
                    }
                ],
                'kode_pos',
                'phone_1',
                'phone_2',
                'email:email',
                'keterangan',
                [
                    'attribute' => 'tgl_masuk',
                    'value'=> function ($model, $index) {
                        return (!empty($model->tgl_masuk)) ? date('d-m-Y', strtotime($model->tgl_masuk)) : null;
                    }
                ],
                [
                    'attribute' => 'tgl_keluar',
                    'value'=> function ($model, $index) {
                        return (!empty($model->tgl_keluar)) ? date('d-m-Y', strtotime($model->tgl_keluar)) : null;
                    }
                ],
                'golongan',
                [
                    'attribute' => 'typeuser_code',
                    'value'=> function ($model, $index) { 
                        return ($model->typeUser) ? $model->typeUser->name : '';
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
</div>