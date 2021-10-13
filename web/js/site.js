/** active menu */
$(function(){
	var path = window.location.href,
		pathSplit = path.split("%")[0] +'%'+ path.split("%")[1];
	$(".navbar-menu-body .navbar-menu a[data-id]").each(function(index, element){
		var _this=$(this);
		if(this.href === path || this.href === pathSplit) {
			_this.closest(".navbar-menu").addClass("active");
			$(".navbar-menu-body").scrollTop((_this.offset().top - ($(".navbar-menu-body").height() - $(".navbar-menu-body").offset().top)));
		}
	});
});

$(document).ready(function(){
    /** reset all element a that have a child menu */
	$(".navbar-menu a i[data-parent]").each(function(i, e) {
		$(e).parent().attr("data-disabled", "disabled");
	});
	$("a[data-disabled]").each(function(i, e) {
		$(e).attr("href", "javascript:void(0)");
	});
    /** end reset all element a that have a child menu */

    /** autocomplete off */
	$("input[type=\"text\"]").attr("autocomplete", "off");

    /** event toggle class menu
        ** 1). search
        ** 2). collapse (navbar left)
		** 3). collapse (navbar mobile)
		** 4). collapse (tree menu by icon)
		** 5). collapse (tree menu by name)
    */
   // 1). search
   $("body").off("input","[data-search=\"menu\"]").on("input","[data-search=\"menu\"]", function(e) {
		e.preventDefault();
		var val = $.trim($(this).val()).replace(/ +/g, " ").toLowerCase();

		$.each($(this).parents(".navbar-search").siblings(".navbar-menu-container").find(".navbar-menu"), function() {
			$(this).show().filter(function(){
				var result = $(this).text().toLowerCase();
				return !~ result.indexOf(val);
			}).hide();
		});
	});
   // 2). collapse (navbar left)
   $("body").off("click","#navbar_slide > .fontello").on("click","#navbar_slide > .fontello", function(e) {
	   e.preventDefault();
	   var _this=$(this),
	   		navSlide = $("#navbar_left_dekstop").attr("data-slide");

        if(_this.hasClass("icon-angle-double-left")){
            $("#navbar_left_dekstop").removeClass("navbar-collapse").removeClass("animation slidein-left slideout-left").addClass("animation slideout-left");
            _this.removeClass("icon-angle-double-left").addClass("icon-angle-double-right");
            $(".container").addClass("container-collapse");
        }else{
            $("#navbar_left_dekstop").removeClass("navbar-collapse").removeClass("animation slideout-left slidein-left").addClass("animation slidein-left");
            _this.removeClass("icon-angle-double-right").addClass("icon-angle-double-left");
            $(".container").removeClass("container-collapse");
        }
    });
    // 3). collapse (navbar mobile)
	$("body").off("click","#toggle_mobile_menu").on("click","#toggle_mobile_menu", function(e) {
		e.preventDefault();
		_this=$(this);
		$("#navbar_left_mobile").toggleClass("open");
		if($("#navbar_left_mobile").hasClass("open")){
			$("#navbar_left_mobile").slideDown();
		}else{
			$("#navbar_left_mobile").slideUp();
		}
	});
    // 4). collapse (tree menu by icon)
	$("body").off("click","[data-role=\"toggle-menu\"]").on("click","[data-role=\"toggle-menu\"]", function(e) {
		e.preventDefault();
		var $dataParent = $(this).attr("data-parent");
		_this=$(this);

		_this.closest(".navbar-menu").toggleClass("open");
		if(_this.closest(".navbar-menu").hasClass("open")){
			_this.removeClass("icon-plus-squared-alt").addClass("icon-minus-squared-alt");
			_this.next().removeClass("icon-folder-3").addClass("icon-folder-open-2");
			if($dataParent == 1){
				_this.closest(".navbar-menu").find(".menu-tree-2").show();
			}else if($dataParent == 2){
				_this.closest(".navbar-menu").find(".menu-tree-3").show();
			}
		}else{
			_this.removeClass("icon-minus-squared-alt").addClass("icon-plus-squared-alt");
			_this.next().removeClass("icon-folder-open-2").addClass("icon-folder-3");
			_this.closest(".navbar-menu").find(".menu-tree").hide();
			if($dataParent == 1){
				// reset class open yang ada di menu-tree
                _this.parent().siblings(".menu-tree").find(".navbar-menu").removeClass("open");
				// reset default
                $("[data-role=\"toggle-menu\"]:not([data-parent=\"1\"])", _this).removeClass("icon-minus-squared-alt").addClass("icon-plus-squared-alt");
				$("[data-role=\"toggle-menu\"]:not([data-parent=\"1\"])", _this).next().removeClass("icon-folder-open-2").addClass("icon-folder-3");
			}
		}
	});
    // 5). collapse (tree menu by name)
    $("body").off("click","a[data-disabled] > span").on("click","a[data-disabled] > span", function(e) {
		e.preventDefault();
		var $dataParent = $(this).siblings("[data-parent]").attr("data-parent");
		_this=$(this).siblings("[data-parent]");
		_this.closest(".navbar-menu").toggleClass("open");
		if(_this.closest(".navbar-menu").hasClass("open")){
			_this.removeClass("icon-plus-squared-alt").addClass("icon-minus-squared-alt");
			_this.next().removeClass("icon-folder-3").addClass("icon-folder-open-2");
			if($dataParent == 1){
				_this.closest(".navbar-menu").find(".menu-tree-2").show();
			}else if($dataParent == 2){
				_this.closest(".navbar-menu").find(".menu-tree-3").show();
			}
		}else{
			_this.removeClass("icon-minus-squared-alt").addClass("icon-plus-squared-alt");
			_this.next().removeClass("icon-folder-open-2").addClass("icon-folder-3");
			_this.closest(".navbar-menu").find(".menu-tree").hide();
			if($dataParent == 1){
				// reset class open yang ada di menu-tree
                _this.parent().siblings(".menu-tree").find(".navbar-menu").removeClass("open");
				// reset default
                $("[data-role=\"toggle-menu\"]:not([data-parent=\"1\"])", _this).removeClass("icon-minus-squared-alt").addClass("icon-plus-squared-alt");
				$("[data-role=\"toggle-menu\"]:not([data-parent=\"1\"])", _this).next().removeClass("icon-folder-open-2").addClass("icon-folder-3");
			}
		}
	});

	$("body").off("click","#btn-remove").on("click","#btn-remove", function(e){
		e.preventDefault();
		$("[data-form]").empty();
	});
});

var temp = {
	init: function() {
		var $button = $([
			'<button class="btn btn-success margin-bottom-20" data-button="change_temp">',
				'<i class="fontello icon-plus"></i>',
				'<span>Update Data Detail</span>',
			'</button>',
			'<button class="btn btn-danger margin-bottom-20 margin-left-5" data-button="cancel">',
				'<i class="fontello icon-cancel"></i>',
				'<span>Cancel</span>',
			'</button>'].join(''));
		
		var $temp = $("[data-button=\"create_temp\"]");
		$temp.removeClass("hidden").addClass("hidden");
		// hapus dulu biar gak numpuk
		$("[data-button=\"change_temp\"]").remove();
		$("[data-button=\"cancel\"]").remove();
		// kemudian render
		$button.insertAfter($temp);
		// disable button update dan delete
		$("[data-button=\"update_temp\"]").prop("disabled", true);
		$("[data-button=\"delete_temp\"]").prop("disabled", true);
		$("button[type=\"submit\"]").prop("disabled", true);

		temp.event();
	},
	destroy: function() {
		$("[data-button=\"create_temp\"]").removeClass("hidden");
		$("[data-button=\"change_temp\"]").remove();
		$("[data-button=\"cancel\"]").remove();
		// enable button update dan delete
		$("[data-button=\"update_temp\"]").prop("disabled", false);
		$("[data-button=\"delete_temp\"]").prop("disabled", false);
		$("button[type=\"submit\"]").prop("disabled", false);
		// clear data temp
		$("[data-temp]").val(null);
	},
	event: function() {
		$("body").off("click", "[data-button=\"cancel\"]").on("click", "[data-button=\"cancel\"]", function(e){
			e.preventDefault();
			temp.destroy();
		});
	}
}