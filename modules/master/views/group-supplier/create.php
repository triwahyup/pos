<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterGroupSupplier */

$this->title = 'Create Group Supplier';
$this->params['breadcrumbs'][] = ['label' => 'Group Supplier', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-group-supplier-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
