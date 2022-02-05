<?php use yii\widgets\ActiveForm; ?>
<?php $form = ActiveForm::begin(['id'=>'form_biaya']); ?>
    <table class="table table-bordered table-custom margin-top-10">
        <thead>
            <tr>
                <th class="text-center"></th>
                <th class="text-center">No.</th>
                <th class="text-center">Nama</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($model) > 0): ?>
                <?php foreach($model as $index=>$val) : ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="proses[]" id="proses_<?=$index ?>">
                        </td>
                        <td class="text-center"><?=$index+1 ?></td>
                        <td><?=$val->name ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td class="text-center text-danger" colspan="4"><i>Data is empty ...</i></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="col-lg-12 col-md-12 col-xs-12 text-right padding-right-0">
        <button class="btn btn-primary" data-button="create_biaya_produksi">
            <i class="fontello icon-floppy"></i>
            <span>Simpan</span>
        </button>
    </div>
<?php ActiveForm::end(); ?>