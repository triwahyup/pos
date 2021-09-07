<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterAccounts */

$this->title = 'Update Data Accounts: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Data Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="master-accounts-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
