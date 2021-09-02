<?php
use yii\helpers\Html;

$this->title = 'Ganti Password';
$this->params['breadcrumbs'][] = 'Ganti Password';
?>
<div class="ganti-password-update">
    <?= $this->render('password_form', [
        'model' => $model,
    ]) ?>
</div>
