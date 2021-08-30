<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
?>
<!-- Button show menu on mobile [default hidden] -->
<div class="toggle-mobile">
    <a class="btn">
        <i class="fontello icon-menu"></i>
    </a>
</div>
<!-- /Button show menu on mobile [default hidden] -->
<div class="appname">
    <?= Html::a('POS <i>Point of Sales</i>', ''); ?>
</div>
<!-- Right Container -->
<ul class="navbar-top-right">
    <li>
        <a href="javascript:void(0)">
            <span>Hari ini: <?=date("d/m/Y") ?></span>
        </a>
    </li>
    <li>
        <a href="javascript:void(0)">
            <span><?= \Yii::$app->user->identity->profile->name ?></span>
        </a>
    </li>
    <li>
        <?= Html::a('Sign Out ', ['/site/logout'], ['data-method' => 'post']) ?>
    </li>
</ul>
<!-- /Right Container -->