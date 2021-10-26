<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\modules\pengaturan\models\PengaturanMenu */
/* @var $form yii\widgets\ActiveForm */

$dataParent1 = [];
$dataParent2 = [];
$dataValue1 = '';
$dataValue2 = '';
if(!$model->isNewRecord){
    if($model->level == 2){
        $dataValue1 = $model->parent->code;
        $dataParent1 = [
            $model->parent->code => $model->parent->name
        ];
    }else if($model->level == 3){
        $dataValue1 = $model->parent->parent_code;
        $dataParent1 = [
            $model->parent->parent_code => $model->parent->parent->name
        ];
        $dataValue2 = $model->parent->code;
        $dataParent2 = [
            $model->parent->code => $model->parent->name
        ];
    }
}
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
                            'data' => $dataParent1,
                            'options' => ['placeholder' => 'Parent 1', 'value' => $dataValue1],
                            'pluginOptions' => ['allowClear' => true],
                        ]) ?>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12 padding-left-0">
                    <?= $form->field($model, 'parent_2')->widget(Select2::classname(), [
                            'data' => $dataParent2,
                            'options' => ['placeholder' => 'Parent 2', 'value' => $dataValue2],
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