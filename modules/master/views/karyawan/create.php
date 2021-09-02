<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterPerson */

$this->title = 'Create Master Karyawan';
$this->params['breadcrumbs'][] = ['label' => 'Master Karyawan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-person-create">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvinsi' => $dataProvinsi,
    ]) ?>
</div>
