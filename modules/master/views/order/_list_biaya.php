<?php use yii\widgets\ActiveForm; ?>
<?php $form = ActiveForm::begin(['id'=>'form_biaya']); ?>
    <table class="table table-bordered table-custom margin-top-10">
        <thead>
            <tr>
                <th class="text-center"></th>
                <th class="text-center">No.</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($data) > 0):
                $no=1; ?>
                <?php foreach($data as $index=>$val) : ?>
                    <tr>
                        <td class="text-center">
                            <?php if(isset($val['id'])): ?>
                                <?php if($val['type'] == 1): ?>
                                    <input type="checkbox" name="biaya[]" value="<?=$val['biaya'] ?>" id="proses_<?=$index ?>" checked>
                                <?php else: ?>
                                    <input type="checkbox" name="biaya[]" value="<?=$val['biaya'] ?>" id="proses_<?=$index ?>" checked disabled>
                                <?php endif; ?>
                            <?php else: ?>
                                <input type="checkbox" name="biaya[]" value="<?=$val['biaya'] ?>" id="proses_<?=$index ?>">
                            <?php endif; ?>
                            <input type="hidden" name="Temp[order_code]" value="<?=$val['order'] ?>">
                            <input type="hidden" name="Temp[item_code]" value="<?=$val['item'] ?>">
                            <input type="hidden" name="Temp[detail_urutan]" value="<?=$val['urutan'] ?>">
                        </td>
                        <td class="text-center"><?=$no++ ?></td>
                        <td><?=$val['name'] ?></td>
                        <td class="text-right"><?=number_format($val['harga']).'.-' ?></td>
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