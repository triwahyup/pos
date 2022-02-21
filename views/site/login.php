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
	<div class="form-wrap">
		<h3 class="text-center">Sign in to POS System</h3>
		<h6 class="text-center">Point of Sales percetakan</h6>
		<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
			<div class="form-group">
				<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
			</div>
			<div class="form-group">
				<?= $form->field($model, 'password',[
					'template' => '{label}{input}
						<a class="plain-text" href="javascript:void(0)" data-button="plaintext" data-field="profile-password">
							<span class="fontello icon-eye-1"></span>
						</a>
					{error}{hint}'])->passwordInput() ?>
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
	<div class="form-layer"></div>
</div>
<script>
$(document).ready(function(){
	$("body").off("click","[data-button=\"plaintext\"]").on("click","[data-button=\"plaintext\"]", function(e){
		e.preventDefault();
		$("#loginform-password").toggleClass("open-text");
		if($("#loginform-password").hasClass("open-text")){
			$("#loginform-password").attr("type", "text");
			$(this).find(".icon-eye-1").removeClass("icon-eye-1").addClass("icon-eye-off");
		}else{
			$("#loginform-password").attr("type", "password");
			$(this).find(".icon-eye-off").removeClass("icon-eye-off").addClass("icon-eye-1");
		}
	});
});
</script>