<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = 'Update Role';
$this->params['breadcrumbs'][] = ['label' => 'Pengaturan'];
$this->params['breadcrumbs'][] = ['label' => 'Role', 'url'=>['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="menu-index">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <fieldset class="fieldset-box">
            <legend>Type: <?=$typeCode->type ?> || Name: <?=$typeCode->name ?></legend>
            <?php $form = ActiveForm::begin() ?>
                <div class="hidden">
                    <?= $form->field($model, 'name')->hiddenInput() ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php foreach($menus as $menu) : ?>
                        <div class="form-group" data-parent="1">
                            <input type="checkbox" name="RoleForm[menu][]" value="<?=$menu->slug ?>" id="<?=$menu->slug ?>" data-type="pizza" data-title="<?=$menu->name ?>">
                            <?php foreach($menu->child as $child) : ?>
                                <div class="form-group" data-parent="2">
                                    <input type="checkbox" name="RoleForm[menu][]" value="<?=$child->slug?>" id="<?=$child->slug ?>" data-type="pizza" data-title="<?=$child->name ?>">
                                    <?php foreach($child->child as $child2) : ?>
										<div class="form-group" data-parent="3">
											<input type="checkbox" name="RoleForm[menu][]" value="<?=$child2->slug ?>" id="<?=$child2->slug ?>" data-type="pizza" data-title="<?=$child2->name ?>">
										</div>
									<?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-lg-12 margin-bottom-20 margin-top-20 text-right">
                    <?= Html::submitButton('<i class="fontello icon-floppy"></i><span>Save</span>', ['class' => 'btn btn-success']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </fieldset>
    </div>
</div>
<script>
$(document).ready(function(){
    <?php if(!empty($model)): ?>
        <?php if(isset($model->menu)):
            foreach($model->menu as $menu): ?>
                $("#<?=$menu?>").prop("checked", true);
                $("#<?=$menu?>").parent().find("i").removeClass("icon-ok").addClass("icon-ok");
            <?php endforeach; 
        endif; ?>
    <?php endif; ?>

    $("body").off("click", "input[type=\"checkbox\"]").on("click", "input[type=\"checkbox\"]", function(e) {
        var $this = $(this);
        $.each($(this), function(index, element){
            var $prop = $this.prop("checked"),
                $parent = $this.parents("[data-parent]");
            if($prop == true){
                if($parent.attr("data-parent") == 1){
                    $this.parent().siblings("[data-parent=\"2\"]").find("input[type=\"checkbox\"]").prop("checked", 1);
                    $this.parent().siblings("[data-parent=\"3\"]").find("input[type=\"checkbox\"]").prop("checked", 1);
                    $this.parent().siblings("[data-parent=\"2\"]").find("i").removeClass("icon-ok").addClass("icon-ok");
                    $this.parent().siblings("[data-parent=\"3\"]").find("i").removeClass("icon-ok").addClass("icon-ok");
                }else if($parent.attr("data-parent") == 2){
                    $this.parent().siblings("[data-parent=\"3\"]").find("input[type=\"checkbox\"]").prop("checked", 1);
                    $this.parent().siblings("[data-parent=\"3\"]").find("i").removeClass("icon-ok").addClass("icon-ok");
                }
            }else{
                if($parent.attr("data-parent") == 1){
                    $this.parent().siblings("[data-parent=\"2\"]").find("input[type=\"checkbox\"]").prop("checked", 0);
                    $this.parent().siblings("[data-parent=\"3\"]").find("input[type=\"checkbox\"]").prop("checked", 0);
                    $this.parent().siblings("[data-parent=\"2\"]").find("i").removeClass("icon-ok");
                    $this.parent().siblings("[data-parent=\"3\"]").find("i").removeClass("icon-ok");
                }else if($parent.attr("data-parent") == 2){
                    $this.parent().siblings("[data-parent=\"3\"]").find("input[type=\"checkbox\"]").prop("checked", 0);
                    $this.parent().siblings("[data-parent=\"3\"]").find("i").removeClass("icon-ok");
                }
            }
        });
    });
});
</script>