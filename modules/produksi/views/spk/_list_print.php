<?php use yii\widgets\ActiveForm; ?>
<?php $form = ActiveForm::begin(['id'=>'print']); ?>
    <table class="table table-bordered table-custom">
        <thead>
            <tr>
                <th class="text-center"></th>
                <th class="text-center">No.</th>
                <th class="text-center">Name</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1;
                foreach($data as $index=>$val) : ?>
                <tr>
                    <td>
                        <input type="checkbox" id="<?=$index ?>" name="spk_print[]" value="<?=$index ?>">
                    </td>
                    <td class="text-center"><?=$no++ ?></td>
                    <td><label class="font-thin margin-bottom-0" for="<?=$index ?>"><?=$val ?></label></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="col-lg-12 col-md-12 col-xs-12 text-right padding-right-0">
        <input type="hidden" name="no_spk" value="<?=$model->no_spk ?>">
        <button class="btn btn-primary" data-button="print">
            <i class="fontello icon-print"></i>
            <span>Print</span>
        </button>
    </div>
<?php ActiveForm::end(); ?>