<div class="page-wrapper">
    <div class="page-layout">
        <div class="page-header">
            <h3><?=strtoupper($type_proses['judul']) ?></h3>
            <table>
                <tr>
                    <th>No. SPK</th>
                    <td class="text-center" width="10">:</td>
                    <td><?='SPK/'.$spkHistory->no_spk ?></td>
                    <td width="350"></td>
                    <th>Tanggal</th>
                    <td class="text-center" width="10">:</td>
                    <td><?=date('d/m/Y', strtotime($spkHistory->tgl_spk)) ?></td>
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
                        <td class="text-left"><?=$spkHistory->uk_potong ?></td>
                        <th width="120"></th>
                        <th class="text-left" width="80">Gram/C-NC</th>
                        <th class="text-center" width="20">:</th>
                        <td class="text-left"><?=$spkHistory->gram.'/C' ?></td>
                    </tr>
                    <tr>
                        <th class="text-left" width="80">Jumlah</th>
                        <th class="text-center" width="20">:</th>
                        <td class="text-left">
                            <?=number_format($spkHistory->qty_proses, 0, ',', '.').' Lembar' ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left" width="80">Job</th>
                        <th class="text-center" width="20">:</th>
                        <td class="text-left"><?=$model->name ?></td>
                    </tr>
                    <?php if($type_proses['type'] == 'pond'): ?>
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
                    <?php if($type_proses['type'] == 'potong'): ?>
                        <tr>
                            <th class="text-left" width="80">Uk. Potong</th>
                            <th class="text-center" width="20">:</th>
                            <td class="text-left"><?=$spkHistory->uk_potong ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if(empty($spkHistory->outsource_code)): ?>
                        <tr>
                            <th class="text-left" width="80">Mesin</th>
                            <th class="text-center" width="20">:</th>
                            <td class="text-left"><?=(!empty($spkHistory->mesin)) ? $spkHistory->mesin->name : '-' ?></td>
                        </tr>
                        <tr>
                            <th class="text-left" width="80">Operator</th>
                            <th class="text-center" width="20">:</th>
                            <td class="text-left"><?=(!empty($spkHistory->operator)) ? $spkHistory->operator->name : '-' ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th class="text-left" width="80">Keterangan</th>
                        <th class="text-center" width="20">:</th>
                        <td class="text-left"><?=(!empty($spkHistory->keterangan)) ? $spkHistory->keterangan : '-' ?></td>
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
<!-- SJ Outsource -->
<?php if(!empty($spkHistory->outsource_code)): ?>
    <div class="page-wrapper">
        <div class="page-layout">
            <div class="page-header">
                <h3>SURAT JALAN OUTSOURCE</h3>
                <table class="table-inline">
                    <tr>
                        <th class="text-left">No. SJ</th>
                        <td class="text-center" width="10">:</td>
                        <td><?='OS/'.$spkHistory->no_sj ?></td>
                    </tr>
                    <tr>
                        <th class="text-left">Tanggal</th>
                        <td class="text-center" width="10">:</td>
                        <td><?=$spkHistory->tgl_spk ?></td>
                    </tr>
                    <tr>
                        <th class="text-left">No. Pol</th>
                        <td class="text-center" width="10">:</td>
                        <td><?=$spkHistory->nopol ?></td>
                    </tr>
                </table>
                <table class="table-inline">
                    <tr>
                        <th>Outsource Partner</th>
                        <td class="text-center" width="10">:</td>
                        <td>
                            <?=$spkHistory->outsource->name ?>
                            <br />
                            <?=(!empty($spkHistory->outsource->address)) ? $spkHistory->outsource->address : '-' ?>
                            <br />
                            <?=(!empty($spkHistory->outsource->kabupaten)) ? str_replace('KABUPATEN', '', $spkHistory->outsource->kabupaten->name) : '-' ?>
                            <br />
                            <?=(!empty($spkHistory->outsource->phone_1)) ? 'HP: '.$spkHistory->outsource->phone_1 : '-' ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="page-body margin-top-0">
                <table class="table-border">
                    <thead>
                        <tr>
                            <th class="text-center" width="250">Job</th>
                            <th class="text-center" width="100">Proses</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-center" width="180">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="padding-bottom-220"><?=$model->name ?></td>
                            <td class="padding-bottom-220 text-center"><?=$spkHistory->proses->name ?></td>
                            <td class="padding-bottom-220 text-center"><?=number_format($spkHistory->qty_proses, 0, ',', '.').' Lembar' ?></td>
                            <td class="padding-bottom-220"><?=(!empty($spkHistory->keterangan)) ? $spkHistory->keterangan : '-' ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="page-footer">
                <table>
                    <tr>
                        <th class="padding-bottom-60 text-center" width="200">Penerima</th>
                        <th class="padding-bottom-60 text-center" width="200">Yang Membuat</th>
                        <th class="padding-bottom-60 text-center" width="200">Gudang</th>
                        <th class="padding-bottom-60 text-center" width="200">Hormat Kami</th>
                    </tr>
                    <tr>
                        <th class="text-center" width="200"><?='('.str_repeat('.', 30).')'?></th>
                        <th class="text-center" width="200"><?='('.str_repeat('.', 30).')'?></th>
                        <th class="text-center" width="200"><?='('.str_repeat('.', 30).')'?></th>
                        <th class="text-center" width="200"><?='('.str_repeat('.', 30).')'?></th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>