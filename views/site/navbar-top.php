<?php
    use app\models\User;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>
<!-- Button show menu on mobile [default hidden] -->
<div class="toggle-mobile">
    <a id="toggle_mobile_menu" class="btn">
        <i class="fontello icon-menu"></i>
    </a>
</div>
<!-- /Button show menu on mobile [default hidden] -->
<div class="appname">
    <?= Html::a('Point of Sales System', ['/site']); ?>
</div>
<!-- Right Container -->
<ul class="navbar-top-right">
    <li>
        <a href="javascript:void(0)">
            <span>Hari ini: <?=date("d/m/Y") ?></span>
        </a>
    </li>
    <li>
        <?php
            $originUser = '';
            $originalId = \Yii::$app->session->get('user.idbeforeswitch');
            if($originalId): 
                $user = User::findOne($originalId);
                $originUser = (isset($user)) ? $user->profile->name : '';
            ?>
            <?= Html::a('<i class="fontello icon-retweet"></i><span>'.$originUser.'</span>', ['/site/switch'], [
                    'data' => [
                        'confirm' => 'Back to Your Account?',
                        'method' => 'post',
                    ],
                    'class' => 'btn-switch-toggle btn-danger',
                    'title' => 'Back to Your Account',
                ]) ?>
        <?php else: ?>
            <a href="javascript:void(0)"><?=\Yii::$app->user->identity->profile->name ?></a>
        <?php endif; ?>
    </li>
    <li>
        <?= Html::a('Sign Out ', ['/site/logout'], ['data-method' => 'post']) ?>
    </li>
</ul>
<!-- /Right Container -->