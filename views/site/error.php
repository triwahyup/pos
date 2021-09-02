<?php
	use yii\helpers\Html;
	$this->title = $name;
	$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-error">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center">
			<img src="img/error.png">
			<h3 class="text-warning"><?=$name ?></h3>
			<h4 class="text-danger"><?=$message ?></h4>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
			<h4 class="smaller">Tautan yang Anda ikuti mungkin telah rusak, atau halaman telah dihapus !</h4>
			<h4 class="smaller">Ikuti petunjuk di bawah ini:</h4>
			<ul>
				<li>
					<i class="fontello icon-right-hand"></i> Cek kembali URL Anda
				</li>
				<li>
					<i class="fontello icon-right-hand"></i> Baca petunjuk website
				</li>
				<li>
					<i class="fontello icon-right-hand"></i> Hubungi Administrator <?= \Yii::$app->name ?>
				</li>
			</ul>
			<div class="col-lg-12 col-md-12 col-xs-12">
				<a href="javascript:history.back()" class="btn btn-default">
					<i class="fontello icon-fast-backward"></i>
					<span>Back</span>
				</a>
				<a href="<?=Yii::$app->homeUrl;?>" class="btn btn-primary">
					<i class="fontello icon-home-2"></i>
					<span>Home</span>
				</a>
			</div>
		</div>
	</div>
</div>