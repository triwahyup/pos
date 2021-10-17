<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\produksi\models\SpkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Spks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="spk-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Spk', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'no_spk',
            'tgl_spk',
            'no_so',
            'tgl_so',
            'keterangan',
            //'status',
            //'status_produksi',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
