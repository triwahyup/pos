<?php use yii\widgets\ActiveForm; ?>
<?php $form = ActiveForm::begin(['id'=>'form_proses']); ?>
    <table class="table table-bordered table-custom margin-top-10">
        <thead>
            <tr>
                <th class="text-center"></th>
                <th class="text-center">No.</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Type</th>
                <th class="text-center">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($data) > 0):
                $no=1; ?>
                <?php foreach($data as $val) : ?>
                    <tr>
                        <td>
                            <?php if(isset($val['id'])): ?>
                                <input type="checkbox" id="<?=$val['proses_code'] ?>" name="TempSalesOrderProses[proses_code][]" value="<?=$val['proses_code'] ?>" checked>
                            <?php else: ?>
                                <input type="checkbox" id="<?=$val['proses_code'] ?>" name="TempSalesOrderProses[proses_code][]" value="<?=$val['proses_code'] ?>">
                            <?php endif; ?>
                        </td>
                        <td class="text-center"><?=$no++ ?></td>
                        <td><label class="font-thin margin-bottom-0" for="<?=$val['proses_code'] ?>"><?=$val['name'] ?></label></td>
                        <td class="text-center"><?=$val['type'] ?></td>
                        <td>
                            <?php if(isset($val['id'])): ?>
                                <textarea class="form-control" name="TempSalesOrderProses[keterangan][<?=$val['proses_code']?>]" rows="2"><?=$val['keterangan'] ?></textarea>
                            <?php else: ?>
                                <textarea class="form-control" name="TempSalesOrderProses[keterangan][<?=$val['proses_code']?>]" rows="2"></textarea>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td class="text-center text-danger" colspan="4"><i>Data is empty ...</i></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <input type="hidden" name="TempSalesOrderProses[code]" value="<?=$tempItem->code ?>">
        <input type="hidden" name="TempSalesOrderProses[item_code]" value="<?=$tempItem->item_code ?>">
        <input type="hidden" name="TempSalesOrderProses[supplier_code]" value="<?=$tempItem->supplier_code ?>">
        <button class="btn btn-primary margin-bottom-20" data-button="create_proses">
            <i class="fontello icon-floppy"></i>
            <span>Simpan</span>
        </button>
    </div>
<?php ActiveForm::end(); ?>