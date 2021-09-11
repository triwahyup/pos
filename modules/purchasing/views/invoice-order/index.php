<?php
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\purchasing\models\PurchaseOrderInvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoice Order';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-invoice-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'no_invoice',
            'tgl_invoice',
            'no_bukti',
            'no_po',
            'supplier_code',
            'total_invoice',
            'post'
        ],
    ]); ?>
</div>