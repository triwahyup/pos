<?php foreach($layouts as $layout): ?>
    <?php if($layout == 'spk_potong'): ?>
        <div class="page-wrapper">
            <div class="page-layout">
                <div class="page-header">
                    <h3><?=strtoupper(str_replace('_', '. ', $layout)) ?></h3>
                    <table>
                        <tr>
                            <th>No. SPK</th>
                            <td class="text-center" width="10">:</td>
                            <td><?='SPK/'.$model->no_spk ?></td>
                            <td width="350"></td>
                            <th>Tanggal</th>
                            <td class="text-center" width="10">:</td>
                            <td><?=date('d/m/Y', strtotime($model->tgl_spk)) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="page-body">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-left" width="80">Relasi</th>
                                <th class="text-center" width="20">:</th>
                                <td class="text-left"><?=$model->customer->name ?></td>
                            </tr>
                            <tr>
                                <th class="text-left" width="80">Uk. Kertas</th>
                                <th class="text-center" width="20">:</th>
                                <td class="text-left"><?=$model->itemMaterial->item->panjang.' x '.$model->itemMaterial->item->lebar ?></td>
                                <th width="120"></th>
                                <th class="text-left" width="80">Gram/C-NC</th>
                                <th class="text-center" width="20">:</th>
                                <td class="text-left"><?=$model->itemMaterial->item->gram.'/C' ?></td>
                            </tr>
                            <tr>
                                <th class="text-left" width="80">Jumlah</th>
                                <th class="text-center" width="20">:</th>
                                <td class="text-left">
                                    <?=number_format(
                                        $model->itemMaterial->inventoryStock->satuanTerkecil($model->itemMaterial->item_code, [
                                            0=>$model->itemMaterial->qty_order_1,
                                            1=>$model->itemMaterial->qty_order_2])
                                        ,0, ',', '.').' Lembar' 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-left" width="80">Job</th>
                                <th class="text-center" width="20">:</th>
                                <td class="text-left"><?=$model->name ?></td>
                            </tr>
                            <tr>
                                <th class="text-left" width="80">Uk. Potong</th>
                                <th class="text-center" width="20">:</th>
                                <td class="text-left">
                                    <table class="table">
                                        <tbody>
                                            <?php foreach($model->potongs as $index=>$val): ?>
                                                <tr>
                                                    <td class="text-center" width="20"><?=$index+1 ?></td>
                                                    <td class="text-center" width="100"><?=$val->panjang.' x '.$val->lebar ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-left" width="80">Keterangan</th>
                                <th class="text-center" width="20">:</th>
                                <td class="text-left">-</td>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="page-footer">
                    <table>
                        <tr>
                            <th class="padding-bottom-60 text-center" width="250">TTD. Penerima:</th>
                            <th class="padding-bottom-60 text-center" width="250">TTD. Kabag Prod:</th>
                            <th class="padding-bottom-60 text-center" width="250">TTD. Adm:</th>
                        </tr>
                        <tr>
                            <th class="text-center" width="250">
                                <?='('.str_repeat('.', 36).')'?>
                                <br />
                                <span class="date-note"><?=date('d/m/Y - H:i:s') ?></span>
                            </th>
                            <th class="text-center" width="250"><?='('.str_repeat('.', 36).')'?></th>
                            <th class="text-center" width="250"><?='('.str_repeat('.', 36).')'?></th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    <?php elseif($layout == 'spk_cetak'): ?>
        <?php foreach($model->potongs as $val): ?>
            <div class="page-wrapper">
                <div class="page-layout">
                    <div class="page-header">
                        <h3><?=strtoupper(str_replace('_', '. ', $layout)) ?></h3>
                        <table>
                            <tr>
                                <th>No. SPK</th>
                                <td class="text-center" width="10">:</td>
                                <td><?='SPK/'.$model->no_spk ?></td>
                                <td width="350"></td>
                                <th>Tanggal</th>
                                <td class="text-center" width="10">:</td>
                                <td><?=date('d/m/Y', strtotime($model->tgl_spk)) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="page-body">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-left" width="80">Relasi</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left"><?=$model->customer->name ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left" width="80">Uk. Kertas</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left"><?=$model->itemMaterial->item->panjang.' x '.$model->itemMaterial->item->lebar ?></td>
                                    <th width="120"></th>
                                    <th class="text-left" width="80">Gram/C-NC</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left"><?=$model->itemMaterial->item->gram.'/C' ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left" width="80">Jumlah</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left"><?=number_format($val->total_objek, 0, ',', '.'). ' / '.$val->objek.'w' ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left" width="80">Job</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left"><?=$model->name ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left" width="80">Keterangan</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left">-</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="page-footer">
                        <table>
                            <tr>
                                <th class="padding-bottom-60 text-center" width="250">TTD. Penerima:</th>
                                <th class="padding-bottom-60 text-center" width="250">TTD. Kabag Prod:</th>
                                <th class="padding-bottom-60 text-center" width="250">TTD. Adm:</th>
                            </tr>
                            <tr>
                                <th class="text-center" width="250">
                                    <?='('.str_repeat('.', 36).')'?>
                                    <br />
                                    <span class="date-note"><?=date('d/m/Y - H:i:s') ?></span>
                                </th>
                                <th class="text-center" width="250"><?='('.str_repeat('.', 36).')'?></th>
                                <th class="text-center" width="250"><?='('.str_repeat('.', 36).')'?></th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php elseif($layout == 'spk_pond'): ?>
        <?php foreach($model->potongs as $val): ?>
            <div class="page-wrapper">
                <div class="page-layout">
                    <div class="page-header">
                        <h3><?=strtoupper(str_replace('_', '. ', $layout)) ?></h3>
                        <table>
                            <tr>
                                <th>No. SPK</th>
                                <td class="text-center" width="10">:</td>
                                <td><?='SPK/'.$model->no_spk ?></td>
                                <td width="350"></td>
                                <th>Tanggal</th>
                                <td class="text-center" width="10">:</td>
                                <td><?=date('d/m/Y', strtotime($model->tgl_spk)) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="page-body">
                        <table>
                            <thead>
                                <tr>
                                    <th class="text-left" width="80">Relasi</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left"><?=$model->customer->name ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left" width="80">Uk. Kertas</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left"><?=$model->itemMaterial->item->panjang.' x '.$model->itemMaterial->item->lebar ?></td>
                                    <th width="120"></th>
                                    <th class="text-left" width="80">Gram/C-NC</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left"><?=$model->itemMaterial->item->gram.'/C' ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left" width="80">Jumlah</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left"><?=number_format($val->total_objek, 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left" width="80">Lbr / Pond</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left">/1 Lbr per Pond</td>
                                </tr>
                                <tr>
                                    <th class="text-left" width="80">Job</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left"><?=$model->name ?></td>
                                </tr>
                                <tr>
                                    <th class="text-left" width="80">Keterangan</th>
                                    <th class="text-center" width="20">:</th>
                                    <td class="text-left">-</td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="page-footer">
                        <table>
                            <tr>
                                <th class="padding-bottom-60 text-center" width="250">TTD. Penerima:</th>
                                <th class="padding-bottom-60 text-center" width="250">TTD. Kabag Prod:</th>
                                <th class="padding-bottom-60 text-center" width="250">TTD. Adm:</th>
                            </tr>
                            <tr>
                                <th class="text-center" width="250">
                                    <?='('.str_repeat('.', 36).')'?>
                                    <br />
                                    <span class="date-note"><?=date('d/m/Y - H:i:s') ?></span>
                                </th>
                                <th class="text-center" width="250"><?='('.str_repeat('.', 36).')'?></th>
                                <th class="text-center" width="250"><?='('.str_repeat('.', 36).')'?></th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endforeach; ?>