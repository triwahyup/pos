<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\master\models\MasterPerson */

$this->title = 'Create Master Person';
$this->params['breadcrumbs'][] = ['label' => 'Master People', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-person-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
