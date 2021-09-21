<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterPerson */

$this->title = 'Create Outsourcing';
$this->params['breadcrumbs'][] = ['label' => 'Data Outsourcing', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-person-create">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvinsi' => $dataProvinsi,
    ]) ?>
</div>