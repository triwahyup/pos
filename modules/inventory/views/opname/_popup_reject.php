<?php
use yii\widgets\ActiveForm;
?>

<div class="popup-form">
	<div class="popup-form-header">
		<h5><?=$title?></h5>
		<a href="javascript:void(0)" class="popup-remove" id="btn-remove">
			<i class="fontello dark-blue icon-cancel-2"></i>
		</a>
	</div>
	<div class="popup-form-body">
		<?php $form = ActiveForm::begin(['id'=>'form']); ?>
			<p class="title"><u><?='Kode: '. $model->code ?></u></p>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center" width="100">Tgl. Opname</th>
						<th class="text-center" width="100">Request By</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-center"><?=$model->date ?></td>
						<td class="text-center"><?=(isset($model->profile)) ? $model->profile->name : '-' ?></td>	
					</tr>
				</tbody>
			</table>
			<div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
				<?= $form->field($approval, 'code')->hiddenInput()->label(false) ?>
				<?= $form->field($approval, 'type')->hiddenInput(['value' => 'REJECT'])->label(false) ?>
				<?= $form->field($approval, 'comment')->textarea() ?>
			</div>
			<div class="text-right">
				<button class="btn btn-danger" data-button="reject">
					<i class="fontello icon-reply"></i>
					<span>REJECT</span>
				</button>
			</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>