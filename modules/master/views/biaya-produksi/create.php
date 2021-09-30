<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterBiayaProduksi */

$this->title = 'Create Biaya Produksi';
$this->params['breadcrumbs'][] = ['label' => 'Biaya Produksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-biaya-produksi-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>