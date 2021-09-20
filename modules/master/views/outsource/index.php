<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\master\models\MasterOutsourceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master People';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="master-person-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Master Person', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'name',
            'address',
            'provinsi_id',
            'kabupaten_id',
            //'kecamatan_id',
            //'kelurahan_id',
            //'kode_pos',
            //'phone_1',
            //'phone_2',
            //'email:email',
            //'fax',
            //'keterangan:ntext',
            //'type_user',
            //'tgl_jatuh_tempo',
            //'group_supplier_code',
            //'status',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
