<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterPerson */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Master People', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="master-person-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'code' => $model->code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'code' => $model->code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'name',
            'address',
            'provinsi_id',
            'kabupaten_id',
            'kecamatan_id',
            'kelurahan_id',
            'kode_pos',
            'phone_1',
            'phone_2',
            'email:email',
            'fax',
            'keterangan:ntext',
            'type_user',
            'tgl_jatuh_tempo',
            'group_supplier_code',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
