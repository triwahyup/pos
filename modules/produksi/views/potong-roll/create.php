<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkPotongRoll */

$this->title = 'Create Potong Roll';
$this->params['breadcrumbs'][] = ['label' => 'Potong Roll', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spk-potong-roll-create">
    <?= $this->render('_form', [
        'model' => $model,
        'temp' => $temp,
    ]) ?>
</div>