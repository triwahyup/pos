<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterAccounts */

$this->title = 'Create Data Accounts';
$this->params['breadcrumbs'][] = ['label' => 'Master Data', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-accounts-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
