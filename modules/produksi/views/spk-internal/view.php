<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkInternal */

$this->title = $model->no_spk;
$this->params['breadcrumbs'][] = ['label' => 'Spk Internals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="spk-internal-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'no_spk' => $model->no_spk], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'no_spk' => $model->no_spk], [
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
            'no_spk',
            'tgl_spk',
            'no_so',
            'tgl_so',
            'keterangan_cetak',
            'keterangan_potong',
            'keterangan_pond',
            'status',
            'status_produksi',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
