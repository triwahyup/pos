<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\produksi\models\Spk */

$this->title = 'No.SPK: '.$model->no_spk;
$this->params['breadcrumbs'][] = ['label' => 'Spk', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="spk-view">
    <p class="text-right">
        <?= Html::a('<i class="fontello icon-pencil"></i><span>Update</span>', ['update', 'no_spk' => $model->no_spk], ['class' => 'btn btn-warning btn-flat btn-sm']) ?>
    </p>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-left-0 padding-right-0">
        
    </div>
</div>