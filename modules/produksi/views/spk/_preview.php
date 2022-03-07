<div class="page-wrapper">
    <div class="page-layout">
        <div class="page-header">
            <h3><?=$header ?></h3>
            <table>
                <tr>
                    <th>No. SPK</th>
                    <td class="text-center" width="10">:</td>
                    <td><?='SPK/'.$spkProduksi->no_spk ?></td>
                    <td width="350"></td>
                    <th>Tanggal</th>
                    <td class="text-center" width="10">:</td>
                    <td><?=date('d/m/Y', strtotime($spkProduksi->tgl_spk)) ?></td>
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
                        <td class="text-left"><?=$spkProduksi->uk_potong ?></td>
                        <th width="120"></th>
                        <th class="text-left" width="80">Gram/C-NC</th>
                        <th class="text-center" width="20">:</th>
                        <td class="text-left"><?=$spkProduksi->gram.'/C' ?></td>
                    </tr>
                    <tr>
                        <th class="text-left" width="80">Jumlah</th>
                        <th class="text-center" width="20">:</th>
                        <td class="text-left">
                            <?=number_format($spkProduksi->qty_proses, 0, ',', '.').' Lembar' ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="80">Job</th>
                        <th class="text-center" width="20">:</th>
                        <td class="text-left"><?=$model->name ?></td>
                    </tr>
                    <?php if($type == 'pond'): ?>
                        <tr>
                            <th class="text-left" width="80">Lbr /Pond</th>
                            <th class="text-center" width="20">:</th>
                            <td class="text-left">1 Lbr per Pond</td>
                        </tr>
                        <tr>
                            <th class="text-left" width="80">Gandeng</th>
                            <th class="text-center" width="20">:</th>
                            <td class="text-left"><?=$model->itemMaterial->total_potong ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if($type == 'potong'): ?>
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
                    <?php endif; ?>
                    <tr>
                        <th class="text-left" width="80">Mesin</th>
                        <th class="text-center" width="20">:</th>
                        <td class="text-left"><?=(!empty($spkProduksi->mesin)) ? $spkProduksi->mesin->name : '-' ?></td>
                    </tr>
                    <tr>
                        <th class="text-left" width="80">Operator</th>
                        <th class="text-center" width="20">:</th>
                        <td class="text-left"><?=(!empty($spkProduksi->operator)) ? $spkProduksi->operator->name : '-' ?></td>
                    </tr>
                    <tr>
                        <th class="text-left" width="80">Keterangan</th>
                        <th class="text-center" width="20">:</th>
                        <td class="text-left"><?=(!empty($spkProduksi->keterangan)) ? $spkProduksi->keterangan : '-' ?></td>
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