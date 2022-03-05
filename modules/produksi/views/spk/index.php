<?php
use app\commands\Helper;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\produksi\models\SpkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Surat Perintah Kerja';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spk-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'no_spk',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'format' => 'raw',
                'label' => 'No. SPK',
                'value' => function($model, $index, $key) {
                    return Html::a($model->no_spk, ['view', 'no_spk' => $model->no_spk]);
                }
            ],
            [
                'attribute' => 'tgl_spk',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel, 
                    'name' => 'tgl_spk', 
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'attribute' => 'tgl_spk',
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                ]),
                'label' => 'Tgl. SPK',
                'value' => function($model, $index, $key){
                    return date('d-m-Y', strtotime($model->tgl_spk));
                }
            ],
            [
                'attribute' => 'no_so',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'label' => 'No. SO',
            ],
            [
                'attribute' => 'tgl_so',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'filter' => DatePicker::widget([
                    'model' => $searchModel, 
                    'name' => 'tgl_so', 
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'attribute' => 'tgl_so',
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                ]),
                'label' => 'Tgl. SO',
                'value' => function($model, $index, $key){
                    return date('d-m-Y', strtotime($model->tgl_so));
                }
            ],
            'name',
            [
                'attribute' => 'status_produksi',
                'format' => 'raw',
                'contentOptions' => [
                    'class' => 'text-center',
                ],
                'value' => function($model, $index, $key)
                {
                    return $model->statusProduksi;
                }
            ],
        ],
    ]); ?>
</div>