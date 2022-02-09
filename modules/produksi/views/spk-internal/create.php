<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\SpkInternal */

$this->title = 'Create Spk Internal';
$this->params['breadcrumbs'][] = ['label' => 'Spk Internal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spk-internal-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>