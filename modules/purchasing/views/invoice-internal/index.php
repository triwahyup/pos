<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\purchasing\models\PurchaseInternalInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Purchase Internal Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-internal-invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Purchase Internal Invoice', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'no_invoice',
            'tgl_invoice',
            'no_bukti',
            'no_po',
            'tgl_po',
            //'tgl_kirim',
            //'term_in',
            //'supplier_code',
            //'keterangan:ntext',
            //'total_ppn',
            //'total_order',
            //'total_invoice',
            //'user_id',
            //'post',
            //'status',
            //'status_terima',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
