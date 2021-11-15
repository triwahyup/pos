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
			<p class="title"><u><?='NO. PO Internal: '. $model->no_pi ?></u></p>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center" width="100">Total Order</th>
						<th class="text-center" width="100">Request By</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="text-right"><?='Rp. '. number_format($model->total_order, 0) .' -.'?></td>
						<td class="text-center"><?=(isset($model->request)) ? $model->request->name : '-' ?></td>	
					</tr>
				</tbody>
			</table>
			<div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
				<?= $form->field($approval, 'no_pi')->hiddenInput()->label(false) ?>
				<?= $form->field($approval, 'type')->hiddenInput(['value' => 'APPROVE'])->label(false) ?>
				<?= $form->field($approval, 'comment')->textarea() ?>
			</div>
			<div class="text-right">
				<button class="btn btn-primary" data-button="approve">
					<i class="fontello icon-ok"></i>
					<span>APPROVE</span>
				</button>
			</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>