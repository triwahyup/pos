<?php
use app\modules\pengaturan\models\PengaturanMenu;
use app\modules\pengaturan\models\PengaturanMenuFavorite;
use yii\helpers\Url;
?>
<!-- MENU SEARCH -->
<div class="navbar-search">
    <input type="text" class="form-control" data-search="menu" placeholder="Search ...">
</div>
<!-- /MENU SEARCH -->
<!-- container collapse (open close menu) -->
<div id="navbar_slide" class="navbar-slide">
    <i class="fontello icon-angle-double-left"></i>
</div>
<!-- /container collapse (open close menu) -->
<!-- MENU CONTAINER -->
<div class="navbar-menu-container">
    <div class="navbar-menu-header">
        <i class="fontello icon-docs-1"></i>
        <p>List Menu</p>
    </div>
    <ul class="navbar-menu-body">
        <?php foreach($menuItems as $item1): ?>
            <!-- MENU HIERARKI 1 -->
            <?php if(!isset($item1['items'])):?>
                <li class="navbar-menu">
                    <a href="<?= Url::to([$item1['url']]) ?>" data-slug="<?= $item1["slug"] ?>" data-id="<?= $item1['menuId'] ?>">
                        <i class="fontello icon-doc-text-inv"></i>
                        <span><?= $item1['label'] ?></span>
                    </a>
                </li>
            <?php else: ?>
                <li class="navbar-menu open">
                    <a href="<?= Url::to([$item1['url']]) ?>" data-slug="<?= $item1["slug"] ?>" data-id="<?= $item1['menuId'] ?>">
                        <i class="fontello icon-minus-squared-alt" data-role="toggle-menu" data-parent="1"></i>
                        <i class="fontello icon-folder-open-2"></i>
                        <span><?= $item1['label'] ?></span>
                    </a>
                    <!-- MENU HIERARKI 2 -->
                    <ul class="menu-tree menu-tree-2" data-toggle="2">
                        <?php foreach($item1['items'] as $item2): ?>
                            <?php if(!isset($item2['items'])):?>
                                <li class="navbar-menu">
                                    <a href="<?= Url::to([$item2['url']]) ?>" data-slug="<?= $item2["slug"] ?>" data-id="<?= $item2['menuId'] ?>">
                                        <i class="fontello icon-doc-text-inv"></i>
                                        <span><?= $item2['label'] ?></span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="navbar-menu menu-tree-2 child open">
                                    <a href="<?= Url::to([$item2['url']]) ?>" data-slug="<?= $item2["slug"] ?>" data-id="<?= $item2['menuId'] ?>">
                                        <i class="fontello icon-minus-squared-alt" data-role="toggle-menu" data-parent="2"></i>
                                        <i class="fontello icon-folder-open-2"></i>
                                        <span><?= $item2['label'] ?></span>
                                    </a>
                                    <!-- MENU HIERARKI 3 -->
                                    <ul class="menu-tree menu-tree-3" data-toggle="3">
                                        <?php foreach($item2['items'] as $item3): ?>
                                            <li class="navbar-menu">
                                                <a href="<?= Url::to([$item3['url']]) ?>" data-slug="<?= $item3["slug"] ?>" data-id="<?= $item3['menuId'] ?>">
                                                    <i class="fontello icon-doc-text-inv"></i>
                                                    <span><?= $item3['label'] ?></span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endif;?>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endif;?>
        <?php endforeach; ?>
    </ul>
</div>
<!-- /MENU CONTAINER -->