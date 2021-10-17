<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\Spk */

$this->title = 'Update Spk: ' . $model->no_spk;
$this->params['breadcrumbs'][] = ['label' => 'Spks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->no_spk, 'url' => ['view', 'no_spk' => $model->no_spk]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="spk-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
