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
        <p class="margin-bottom-0">
            <strong>Keterangan:</strong>
        </p>
        <p class="margin-bottom-0">
            <strong class="text-danger">C</strong>: Akses untuk Create (Membuat Data Baru).</p>
        <p class="margin-bottom-0">
            <strong class="text-danger">R</strong>: Akses untuk Read (Hanya Melihat Data).</p>
        <p class="margin-bottom-0">
            <strong class="text-danger">U</strong>: Akses untuk Update (Merubah Data).</p>
        <p class="margin-bottom-0">
            <strong class="text-danger">D</strong>: Akses untuk Delete (Hapus Data).</p>
        <p class="margin-bottom-0">
            <strong class="text-danger">A</strong>: Akses untuk Approval.</p>
        <fieldset class="fieldset-box margin-top-30">
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
                                    <div class="form-group" data-parent="3">
                                        <input type="checkbox" name="RoleForm[menu][]" value="<?=$child->slug.'[C]'?>" id="<?=$child->slug.'-C' ?>" data-type="pizza" data-title="C">
                                        <input type="checkbox" name="RoleForm[menu][]" value="<?=$child->slug.'[R]'?>" id="<?=$child->slug.'-R' ?>" data-type="pizza" data-title="R">
                                        <input type="checkbox" name="RoleForm[menu][]" value="<?=$child->slug.'[U]'?>" id="<?=$child->slug.'-U' ?>" data-type="pizza" data-title="U">
                                        <input type="checkbox" name="RoleForm[menu][]" value="<?=$child->slug.'[D]'?>" id="<?=$child->slug.'-D' ?>" data-type="pizza" data-title="D">
                                        <input type="checkbox" name="RoleForm[menu][]" value="<?=$child->slug.'[A]'?>" id="<?=$child->slug.'-A' ?>" data-type="pizza" data-title="A">
                                    </div>
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
            foreach($model->menu as $menu):
                $menu = str_replace('[', '-', $menu);
                $menu = str_replace(']', '', $menu); ?>
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
                    $this.parent().siblings("[data-parent=\"2\"]")
                        .children(".checkbox-container").find("input[type=\"checkbox\"]").prop("checked", 1);
                    $this.parent().siblings("[data-parent=\"2\"]")
                        .children(".checkbox-container").find("i").removeClass("icon-ok").addClass("icon-ok");
                    $this.parent().siblings("[data-parent=\"2\"]")
                        .find("[data-parent=\"3\"]").find("[data-title=\"R\"]").prop("checked", 1);
                    $this.parent().siblings("[data-parent=\"2\"]").find("[data-parent=\"3\"]").find("[data-title=\"R\"]")
                        .siblings(".checkbox-wrapper").find("i").removeClass("icon-ok").addClass("icon-ok");
                }
                else if($parent.attr("data-parent") == 2){
                    $this.parents("[data-parent=\"1\"]").children(".checkbox-container")
                        .find("input[type=\"checkbox\"]").prop("checked", 1);
                    $this.parents("[data-parent=\"1\"]").children(".checkbox-container")
                        .find(".checkbox-wrapper").find("i").removeClass("icon-ok").addClass("icon-ok");
                    $this.parent().siblings("[data-parent=\"3\"]").find("[data-title=\"R\"]").prop("checked", 1);
                    $this.parent().siblings("[data-parent=\"3\"]").find("[data-title=\"R\"]")
                        .siblings(".checkbox-wrapper").find("i").removeClass("icon-ok").addClass("icon-ok");
                }
                else if($parent.attr("data-parent") == 3){
                    $this.parents("[data-parent=\"2\"]")
                        .siblings(".checkbox-container").find("input[type=\"checkbox\"]").prop("checked", 1);
                    $this.parents("[data-parent=\"2\"]")
                        .siblings(".checkbox-container").find("i").removeClass("icon-ok").addClass("icon-ok");
                    $this.parents("[data-parent=\"3\"]")
                        .siblings(".checkbox-container").find("input[type=\"checkbox\"]").prop("checked", 1);
                    $this.parents("[data-parent=\"3\"]")
                        .siblings(".checkbox-container").find("i").removeClass("icon-ok").addClass("icon-ok");
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