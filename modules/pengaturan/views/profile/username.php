<?php
use yii\helpers\Html;

$this->title = 'Ganti Username [Profile]';
$this->params['breadcrumbs'][] = 'Ganti Username [Profile]';
?>
<div class="ganti-profile-update">
    <?= $this->render('username_form', [
        'model' => $model,
        'profile' => $profile,
        'typeCode' => $typeCode,
    ]) ?>
</div>
