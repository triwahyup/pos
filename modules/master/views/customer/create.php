<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterPerson */

$this->title = 'Create Master Customer';
$this->params['breadcrumbs'][] = ['label' => 'Master Customer', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-person-create">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvinsi' => $dataProvinsi,
    ]) ?>
</div>
