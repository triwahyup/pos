<?php
$this->title = "Dashboard";
$this->params['breadcrumbs'][] = "Dashboard";
?>
<div class="dashboard-container">
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <div class="col-lg-6 -col-md-6 col-xs-12 padding-left-0 padding-right-0">
            <div class="cube-container cube-info">
                <div class="cube-left">
                    <i class="fontello icon-info-circled-3"></i></span>
                </div>
                <div class="cube-right">
                    <?php if($userApproval): ?>
                        <?='<p>Anda memiliki request '.$countPurchaseApp.' approval</p>'?>
                    <?php else: ?>
                        <p>List Request Approval:</p>
                    <?php endif; ?>
                    <ul>
                        <?php if($countPurchaseApp > 0): ?>
                            <?=$listApproval?>
                        <?php else: ?>
                            <li>
                                <span>Request Approval is Empty.</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>