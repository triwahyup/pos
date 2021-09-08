<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterPerson */

$this->title = 'Create Data Supplier';
$this->params['breadcrumbs'][] = ['label' => 'Data Supplier', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-person-create">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvinsi' => $dataProvinsi,
        'groupSupplier' => $groupSupplier,
    ]) ?>
</div>
