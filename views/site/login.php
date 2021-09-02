<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-container">
	<div class="login-form">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="form-wrap">
				<h3 class="text-center">Sign in to POS System</h3>
				<h6 class="text-center">Point of Sales percetakan</h6>
				<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
					<div class="form-group">
						<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
					</div>
					<div class="form-group">
						<?= $form->field($model, 'password')->passwordInput() ?>
					</div>
					<div class="form-group">
						<div class="checkbox checkbox-primary pull-left offpadding">
							<?= $form->field($model, 'rememberMe')->checkbox([
								'template' => "<div class=\"ml20\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
							]) ?>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="form-group text-center col-sm-12">
						<?= Html::submitButton('Login', ['class' => 'btn btn-default', 'name' => 'login-button']) ?>
					</div>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>