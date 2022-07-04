<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterProses */

$this->title = 'Create Data Proses';
$this->params['breadcrumbs'][] = ['label' => 'Data Proses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-proses-create">
    <?= $this->render('_form', [
        'model' => $model,
        'dataList' => $dataList,
    ]) ?>
</div>