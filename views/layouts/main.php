<?php
use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\helpers\Url;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
        <head>
            <meta charset="<?= Yii::$app->charset ?>">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <?php $this->registerCsrfMetaTags() ?>
            <title><?= Html::encode($this->title) ?></title>
            <link rel="shortcut icon" href="<?= \Yii::$app->request->baseUrl ?>/img/favicon.png" type="image/x-icon" />
            <?php $this->head() ?>
        </head>
        <body class="d-flex flex-column h-100">
            <?php $this->beginBody() ?>
                <div class="wrap">
                    <div class="navbar">
                        <div id="navbar_top" class="navbar-top"></div>
                        <div id="navbar_left_dekstop" class="navbar-left navbar-collapse"  data-slide="close"></div>
                        <div id="navbar_left_mobile" class="navbar-left navbar-mobile"></div>
                    </div>
                    <main role="main" class="flex-shrink-0">
                        <div class="container">
                            <?= Breadcrumbs::widget([
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ]) ?>
                            <?= Alert::widget() ?>
                        
                            <div class="formbox-container">
                                <div class="formbox-header">
                                    <h5><?= $this->title ?></h5>
                                </div>
                                <div class="formbox-body">
                                    <div id="render_content"><?= $content ?></div>
                                </div>
                            </div>
                            <div class="content-cannot-access">
                                <p class="text-danger">Can not access application under 320px. Please update your screen.</p>
                            </div>
                        </div>
                    </main>
                </div>
            <?php $this->endBody() ?>
        </body>
    </html>
<?php $this->endPage() ?>

<script>
function loadNavbarTop()
{
    var urlScript = "<?= Url::to('js/site.js') ?>";
    $.ajax({
        url: '<?= Url::to(['/site/navbar-top']) ?>',
        type: "POST",
        success: function(data) {
            var o = $.parseJSON(data);
            $("#navbar_top").html(o.data);
        },
        complete: function(){
            $.getScript( urlScript )
                .done(function(script, status){
                    console.log("Load site.min.js is "+ status);
                })
            .fail(function(xhr, message, exception){
                console.log("Error load site.min.js: "+ exception);
            });
        }
    });
}

function loadNavbarLeft()
{
    $.ajax({
        url: '<?= Url::to(['/site/navbar-left']) ?>',
        type: "POST",
        success: function(data) {
            var o = $.parseJSON(data);
            $("#navbar_left_dekstop").html(o.data);
            $("#navbar_left_mobile").html(o.data);
        }
    });
}

$(document).ready(function(){
    // load navbar top / header
    loadNavbarTop();
    // load menu navbar left
    loadNavbarLeft();
});
</script>