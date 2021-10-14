<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\pengaturan\models\PengaturanMenu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pengaturan-menu-form">
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
                    <?= $form->field($model, 'type_code')->widget(Select2::classname(), [
                        'data' => $typeMenu,
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
                    <?= $form->field($model, 'parent_1')->widget(Select2::classname(), [
                            'data' => (!$model->isNewRecord) ? [$model->code => $model->name] : [],
                            'options' => ['placeholder' => 'Parent 1', 'value' => !$model->isNewRecord ? $model->code : ''],
                            'pluginOptions' => ['allowClear' => true],
                        ]) ?>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'parent_2')->widget(Select2::classname(), [
                            'data' => (!$model->isNewRecord) ? [$model->parent_code => $model->parent->name] : [],
                            'options' => ['placeholder' => 'Parent 2', 'value' => !$model->isNewRecord ? $model->parent_code : ''],
                            'pluginOptions' => ['allowClear' => true],
                        ]) ?>
                </div>
            </div>
        </div>
        <div class="form-group text-right">
            <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
<script>
function listParentMenu(position, level=1, parent=null)
{
    $.ajax({
        url: "<?=Url::to(['menu/list']) ?>",
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
                var opt = new Option(value.name, value.code, false, false);
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