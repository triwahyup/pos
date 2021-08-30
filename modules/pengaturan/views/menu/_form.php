<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\pengaturan\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
        <div class="col-lg-4 col-md-8 col-sm-12 col-xs-12 padding-left-0">
            <div class="col-lg-9 col-md-9 col-xs-12 padding-left-0">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
                <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 padding-left-0">
                <?= $form->field($model, 'position')->widget(Select2::classname(), [
                    'data' => [
                        1 => 'Menu Navbar Top',
                        2 => 'Menu Navbar Left',
                    ],
                    'options' => ['placeholder' => 'Position'],
                    'pluginEvents' => [
                        'change' => 'function() {
                            listParentMenu($(this).val());
                        }',
                    ],
                ]) ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 padding-left-0">
                <?= $form->field($model, 'urutan')->textInput() ?>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                <?= $form->field($model, 'parent_1')->widget(Select2::classname(), ['data' => []]) ?>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                <?= $form->field($model, 'parent_2')->widget(Select2::classname(), ['data' => []]) ?>
            </div>
        </div>
        <div class="hidden">
            <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="col-lg-12 col-md-12 col-xs-12 padding-left-0">
    </div>
    <div class="text-right">
        <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function listParentMenu(position, level=1, parent=null)
    {
        $.ajax({
            url: "<?=Url::to(['menu/parent-menu']) ?>",
            type: "POST",
            data: {
                position: position,
                level: level,
                parent: parent
            },
            dataType: "text",
			error: function(xhr, status, error) {},
            beforeSend: function(){},
            success: function(data){
                var o = $.parseJSON(data);
                $("#pengaturanmenu-parent_"+level).empty();
				$.each(o, function(index, value){
					var opt = new Option(value.name, value.id, false, false);
					$("#pengaturanmenu-parent_"+level).append(opt);
				});
				$("#pengaturanmenu-parent_"+level).val(null);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){},
            complete: function(){}
        });
    }

    $(document).ready(function(){
        $("body").off("change","#pengaturanmenu-parent_1").on("change","#pengaturanmenu-parent_1", function(e){
            e.preventDefault();
            listParentMenu(2, 2, $(this).val());
        });
    });
</script>