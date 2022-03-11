<?php
$this->title = "Dashboard";
$this->params['breadcrumbs'][] = "Dashboard";
?>
<div class="dashboard-container">
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <!-- PURCHASE ORDER -->
        <div class="col-lg-6 -col-md-6 col-xs-12 padding-left-0">
            <div class="cube-container cube-success">
                <div class="cube-left">
                    <i class="fontello icon-info-circled-3"></i></span>
                </div>
                <div class="cube-right">
                    <?php if($userApproval): ?>
                        <?='<p>(Purchase Order) Anda memiliki request '.$countPurchaseApp.' approval</p>'?>
                    <?php else: ?>
                        <p>Purchase Order List Approval:</p>
                    <?php endif; ?>
                    <ul>
                        <?php if($countPurchaseApp > 0): ?>
                            <?=$listPurchaseApp?>
                        <?php else: ?>
                            <li>
                                <span>Request Approval is Empty.</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /PURCHASE ORDER -->
        <!-- PO INTERNAL -->
        <div class="col-lg-6 -col-md-6 col-xs-12 padding-left-0 padding-right-0">
            <div class="cube-container cube-danger">
                <div class="cube-left">
                    <i class="fontello icon-info-circled-3"></i></span>
                </div>
                <div class="cube-right">
                    <?php if($userApproval): ?>
                        <?='<p>(PO Internal) Anda memiliki request '.$countPoInternalApp.' approval</p>'?>
                    <?php else: ?>
                        <p>PO Internal List Approval:</p>
                    <?php endif; ?>
                    <ul>
                        <?php if($countPoInternalApp > 0): ?>
                            <?=$listPoInternalApp?>
                        <?php else: ?>
                            <li>
                                <span>Request Approval is Empty.</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /PO INTERNAL -->
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0 padding-right-0">
        <!-- REQUEST ITEM -->
        <div class="col-lg-6 -col-md-6 col-xs-12 padding-left-0">
            <div class="cube-container cube-drop">
                <div class="cube-left">
                    <i class="fontello icon-info-circled-3"></i></span>
                </div>
                <div class="cube-right">
                    <?php if($userApproval): ?>
                        <?='<p>(Request Order) Anda memiliki request '.$countRequestOrderApp.' approval</p>'?>
                    <?php else: ?>
                        <p>Request Order List Approval:</p>
                    <?php endif; ?>
                    <ul>
                        <?php if($countRequestOrderApp > 0): ?>
                            <?=$listRequestOrderApp?>
                        <?php else: ?>
                            <li>
                                <span>Request Approval is Empty.</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /REQUEST ITEM -->
        <!-- STOCK OPNAME -->
        <div class="col-lg-6 -col-md-6 col-xs-12 padding-left-0 padding-right-0">
            <div class="cube-container cube-warning">
                <div class="cube-left">
                    <i class="fontello icon-info-circled-3"></i></span>
                </div>
                <div class="cube-right">
                    <?php if($userApproval): ?>
                        <?='<p>(Stock Opname) Anda memiliki request '.$countStockOpnameApp.' approval</p>'?>
                    <?php else: ?>
                        <p>Stock Opname List Approval:</p>
                    <?php endif; ?>
                    <ul>
                        <?php if($countStockOpnameApp > 0): ?>
                            <?=$listStockOpnameApp?>
                        <?php else: ?>
                            <li>
                                <span>Request Approval is Empty.</span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /STOCK OPNAME -->
    </div>
</div>