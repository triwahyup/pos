<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterKendaraan */

$this->title = 'Create Master Kendaraan';
$this->params['breadcrumbs'][] = ['label' => 'Master Kendaraan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-kendaraan-create">
    <?= $this->render('_form', [
        'model' => $model,
        'dataList' => $dataList,
    ]) ?>
</div>