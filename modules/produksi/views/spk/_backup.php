<div class="col-lg-6 col-md-6 col-xs-12">
    <!-- Lb. Ikat -->
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <label>Lb. Ikat</label>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 padding-right-0">:</div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <span class="font-size-10">
                <?=(!empty($model->itemMaterial->lembar_ikat_1) ? number_format($model->itemMaterial->lembar_ikat_1) .' '.$model->itemMaterial->lembar_ikat_um_1 .' / ' : '') ?>
                <?=(!empty($model->itemMaterial->lembar_ikat_2) ? number_format($model->itemMaterial->lembar_ikat_2) .' '.$model->itemMaterial->lembar_ikat_um_2 .' / ' : '') ?>
                <?=(!empty($model->itemMaterial->lembar_ikat_3) ? number_format($model->itemMaterial->lembar_ikat_3) .' '.$model->itemMaterial->lembar_ikat_um_3 : '') ?>
            </span>
        </div>
    </div>
    <!-- Total Potong -->
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <label>Total Potong</label>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 padding-right-0">:</div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <span class="font-size-12"><?=$model->itemMaterial->total_potong.'<span class="text-muted font-size-10"> ('.number_format($model->itemMaterial->jumlah_cetak).' cetak)</span>' ?></span>
        </div>
    </div>
    <!-- Total Warna -->
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <label>Total Warna</label>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 padding-right-0">:</div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
            <span class="font-size-12"><?=$model->itemMaterial->total_warna ?></span>
        </div>
    </div>
</div>
<?php
    function getUpProduksi()
    {
        $str = '<strong class="font-size-12">';
        $str .= (!empty($this->up_produksi || $this->up_produksi != 0)) ? $this->up_produksi.'%' : 0;
        $str .= '</strong>';
        $str .= '<span class="text-muted font-size-12">';
        
        if(!empty($this->up_produksi) || $this->up_produksi != 0){
            $stock = 0;
            $stockItem = $this->itemMaterial->inventoryStock;
            if(isset($stockItem)){
                $stock = $stockItem->satuanTerkecil($this->itemMaterial->item_code, [
                    0=>$this->itemMaterial->qty_order_1,
                    1=>$this->itemMaterial->qty_order_2
                ]);
            }
            $upproduksi = $stock * ($this->up_produksi/100);
    
            $str .= ' ('.number_format($upproduksi).' Lembar)';
            $str .= '</span>';
        }
        return $str;
    }

    function getTotalPotong()
    {
        $str = '<span class="font-size-12">';
        $total_potong = $this->itemMaterial->total_potong;
        $jumlahCetak = 0;
        if(!empty($this->up_produksi) || $this->up_produksi != 0){
            $stock = 0;
            $stockItem = $this->itemMaterial->inventoryStock;
            if(isset($stockItem)){
                $stock = $stockItem->satuanTerkecil($this->itemMaterial->item_code, [
                    0=>$this->itemMaterial->qty_order_1,
                    1=>$this->itemMaterial->qty_order_2
                ]);
            }
            $upproduksi = $stock * ($this->up_produksi/100);
            $jumlahCetak = $this->itemMaterial->jumlah_cetak+($upproduksi*$total_potong);
        }else{
            $jumlahCetak = $this->itemMaterial->jumlah_cetak;
        }
        $str .= $total_potong;
        $str .= '<span class="text-muted font-size-10">';
        $str .= ' ('.number_format($jumlahCetak).' cetak)';
        $str .= '</span>';
        $str .= '</span>';
        return $str;
    }
?>

<?php foreach($layouts as $layout): ?>
    <?php if($layout == 'spk_potong'): ?>
        
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