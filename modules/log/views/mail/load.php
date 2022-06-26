<div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
    <table class="table table-bordered table-custom margin-bottom-0 margin-top-10" id="logs_mail">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Type</th>
                <th class="text-center">Email</th>
                <th class="text-center">Cc</th>
                <th class="text-center">Bcc</th>
                <th class="text-center">Subject</th>
                <th class="text-center">Body</th>
                <th class="text-center">Keterangan</th>
                <th class="text-center">Status</th>
                <th class="text-center">Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($model) > 0): ?>
                <?php foreach($model as $val): ?>
                    <tr>
                        <td class="text-center"><?=$no++ ?></td>
                        <td class="text-center"><?=$val['type'] ?></td>
                        <td><?=$val['email'] ?></td>
                        <td><?=$val['cc'] ?></td>
                        <td><?=$val['bcc'] ?></td>
                        <td><?=$val['subject'] ?></td>
                        <td><?=$val['body'] ?></td>
                        <td><?=$val['keterangan'] ?></td>
                        <td class="text-center"><?=$val['status'] ?></td>
                        <td class="text-center"><?=date('d-m-Y', $val['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="text-danger" colspan="10">Data masih kosong.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="pull-right">
        <?php if($paging['pages'] > 1) : ?>
            <ul class="pagination">
                <?php if($paging['curr_page'] > 1) : ?>
                    <li class="text-first">
                        <span title="Halaman Pertama" data-page="1">First</span>
                    </li>
                    <li class="text-prev">
                        <span title="Halaman Sebelumnya" data-page="<?=($paging['curr_page'] -1)?>">Prev</span>
                    </li>
                <?php endif; ?>
                <?php
                    $max = 3;
                    if($paging['curr_page'] < $max)
                        $sp = 1;
                    elseif($paging['curr_page'] >= ($paging['pages'] - floor($max / 2)))
                        $sp = $paging['pages'] - $max + 1;
                    elseif($paging['curr_page'] >= $max)
                        $sp = $paging['curr_page']  - floor($max/2);
                ?>

                <?php for($i = $sp; $i <= ($sp + $max -1);$i++) : ?>
                    <?php if($i > $paging['pages']) continue; ?>
                    <?php if($paging['curr_page'] == $i) : ?>
                        <li class="active"><span  data-page="<?=$i?>"><?= $i; ?></span></li>
                    <?php else : ?>
                        <li><span  data-page="<?=$i?>" title="Halaman <?= $i; ?>"><?= $i; ?></span></li>					
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if($paging['pages'] < ($paging['pages'] - floor($max / 2))) : ?>
                    <li><span>..</span></li>
                    <li><span title="Halaman <?= $paging['pages'] ?>"  data-page="<?=$paging['pages'] ?>"><?= $paging['pages'] ?></span></li>
                <?php endif; ?>
                <?php if($paging['curr_page'] < $paging['pages']) : ?>
                    <li class="text-next">
                        <span title="Halaman Selanjutnya"  data-page="<?=($paging['curr_page'] +1)?>">Next</span>
                    </li>
                    <li class="text-last">
                        <span title="Halaman Terakhir" data-page="<?=$paging['pages'] ?>">Last</span>
                    </li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>