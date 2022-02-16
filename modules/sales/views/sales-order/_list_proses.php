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
            <?php if(count($data) > 0):
                $no=1; ?>
                <?php foreach($data as $index=>$val) : ?>
                    <tr>
                        <td>
                            <?php if(isset($val['id'])): ?>
                                <input type="checkbox" name="TempSalesOrderProses[biaya_code][]" value="<?=$val['biaya_code'] ?>" checked>
                                <?php else: ?>
                                    <input type="checkbox" name="TempSalesOrderProses[biaya_code][]" value="<?=$val['biaya_code'] ?>">
                                <?php endif; ?>
                        </td>
                        <td class="text-center"><?=$no++ ?></td>
                        <td><?=$val['name'] ?></td>
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
        <input type="hidden" name="TempSalesOrderProses[code]" value="<?=$tempPotong->code ?>">
        <input type="hidden" name="TempSalesOrderProses[item_code]" value="<?=$tempPotong->item_code ?>">
        <input type="hidden" name="TempSalesOrderProses[urutan]" value="<?=$tempPotong->urutan ?>">
        <button class="btn btn-primary" data-button="create_proses">
            <i class="fontello icon-floppy"></i>
            <span>Simpan</span>
        </button>
    </div>
<?php ActiveForm::end(); ?>