<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
$this->params['breadcrumbs'][] = "Error Page";
?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert">
        <?= nl2br(Html::encode($message)) ?>
    </div>
    <p>
        The above error occurred while the Web server was processing your request.
        Please contact us if you think this is a server error. Thank you.
    </p>
</div>