var properties = false;

var navigasi = {
    // function loading saat add to menu favorite
    process: function(params) {
		if(params == "load") {
			var content = "<div class=\"loading-layer\"><div id=\"loading_id\"></div></div>";
			$(content).appendTo(".navbar-favorite-body");
			$("#loading_id").loader("load", {left:-125, top:80});
		} else if(params == "destroy") {
			$("#close_properties").trigger("click");
			$("#loading_id").loader("destroy");
			$(".loading-layer").remove();
		}
	},
    // add class active in menu active
    activeMenu: function() {
		var path = window.location.href,
			pathSplit = path.split("%")[0] +'%'+ path.split("%")[1];
		$(".navbar-menu-body .navbar-menu a[data-id]").each(function(index, element){
            console.log("2", this.href);
			var _this=$(this);
            console.log(this.href, path)
			if(this.href === path || this.href === pathSplit) {
				_this.closest(".navbar-menu").addClass("active");
				$(".navbar-menu-body").scrollTop((_this.offset().top - ($(".navbar-menu-body").height() - $(".navbar-menu-body").offset().top)));
			}
		});
	},
};

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

    /** event search list menu */
	$("body").off("input","[data-search=\"menu\"]").on("input","[data-search=\"menu\"]", function(e) {
		e.preventDefault();
		var val = $.trim($(this).val()).replace(/ +/g, " ").toLowerCase(),
			$container;
		if(is.screen == "dekstop_version") {
			$container = ".navbar-menu-container";
		} else if(is.screen == "mobile_version") {
			$container = ".navbar-menu-container-mobile";
		}

		$.each($(this).parents(".navbar-search").siblings($container).find(".navbar-menu"), function() {
			$(this).show().filter(function(){
				var result = $(this).text().toLowerCase();
				return !~ result.indexOf(val);
			}).hide();
		});
	});
	/** end event search list menu */

    navigasi.activeMenu();

    /** event toggle class menu
        ** 1). navbar left collapse
		** 2). navbar mobile collapse
		** 3). navbar left menu tree by icon
		** 4). navbar left menu tree by name
    */
   // 1). navbar left collapse
    $("body").off("click",".navbar-slide > .fontello").on("click",".navbar-slide > .fontello", function(e) {
        e.preventDefault();
        var _this=$(this),
            navSlide = $(".navbar-left").attr("data-slide");

        if(_this.hasClass("icon-angle-double-left")) {
            $(".navbar-left").removeClass("navbar-collapse").removeClass("animation slidein-left slideout-left").addClass("animation slideout-left");
            _this.removeClass("icon-angle-double-left").addClass("icon-angle-double-right");
            $(".container").addClass("container-collapse");
        } else {
            $(".navbar-left").removeClass("navbar-collapse").removeClass("animation slideout-left slidein-left").addClass("animation slidein-left");
            _this.removeClass("icon-angle-double-right").addClass("icon-angle-double-left");
            $(".container").removeClass("container-collapse");
        }
    });
    // 2). navbar mobile collapse
	$("body").off("click",".navbar-toggle-mobile").on("click",".navbar-toggle-mobile", function(e) {
		e.preventDefault();
		_this=$(this);
		$(".navbar-left-mobile").toggleClass("open");
		if($(".navbar-left-mobile").hasClass("open")) {
			$(".navbar-left-mobile").slideDown();
		} else {
			$(".navbar-left-mobile").slideUp();
		}
	});
    // 3). navbar left menu tree by icom
	$("body").off("click","[data-role=\"toggle-menu\"]").on("click","[data-role=\"toggle-menu\"]", function(e) {
		e.preventDefault();
		var $dataParent = $(this).attr("data-parent");
		_this=$(this);

		_this.closest(".navbar-menu").toggleClass("open");
		if(_this.closest(".navbar-menu").hasClass("open")) {
			_this.removeClass("icon-plus-squared-alt").addClass("icon-minus-squared-alt");
			_this.next().removeClass("icon-folder-3").addClass("icon-folder-open-2");
			if($dataParent == 1) {
				_this.closest(".navbar-menu").find(".menu-tree-2").show();
			} else if($dataParent == 2) {
				_this.closest(".navbar-menu").find(".menu-tree-3").show();
			}
		} else {
			_this.removeClass("icon-minus-squared-alt").addClass("icon-plus-squared-alt");
			_this.next().removeClass("icon-folder-open-2").addClass("icon-folder-3");
			_this.closest(".navbar-menu").find(".menu-tree").hide();
			if($dataParent == 1) {
				// reset class open yang ada di menu-tree
                _this.parent().siblings(".menu-tree").find(".navbar-menu").removeClass("open");
				// reset default
                $("[data-role=\"toggle-menu\"]:not([data-parent=\"1\"])", _this).removeClass("icon-minus-squared-alt").addClass("icon-plus-squared-alt");
				$("[data-role=\"toggle-menu\"]:not([data-parent=\"1\"])", _this).next().removeClass("icon-folder-open-2").addClass("icon-folder-3");
			}
		}
	});
    // 4). navbar left menu tree by name
    $("body").off("click","a[data-disabled] > span").on("click","a[data-disabled] > span", function(e) {
		e.preventDefault();
		var $dataParent = $(this).siblings("[data-parent]").attr("data-parent");
		_this=$(this).siblings("[data-parent]");
		_this.closest(".navbar-menu").toggleClass("open");
		if(_this.closest(".navbar-menu").hasClass("open")) {
			_this.removeClass("icon-plus-squared-alt").addClass("icon-minus-squared-alt");
			_this.next().removeClass("icon-folder-3").addClass("icon-folder-open-2");
			if($dataParent == 1) {
				_this.closest(".navbar-menu").find(".menu-tree-2").show();
			} else if($dataParent == 2) {
				_this.closest(".navbar-menu").find(".menu-tree-3").show();
			}
		} else {
			_this.removeClass("icon-minus-squared-alt").addClass("icon-plus-squared-alt");
			_this.next().removeClass("icon-folder-open-2").addClass("icon-folder-3");
			_this.closest(".navbar-menu").find(".menu-tree").hide();
			if($dataParent == 1) {
				// reset class open yang ada di menu-tree
                _this.parent().siblings(".menu-tree").find(".navbar-menu").removeClass("open");
				// reset default
                $("[data-role=\"toggle-menu\"]:not([data-parent=\"1\"])", _this).removeClass("icon-minus-squared-alt").addClass("icon-plus-squared-alt");
				$("[data-role=\"toggle-menu\"]:not([data-parent=\"1\"])", _this).next().removeClass("icon-folder-open-2").addClass("icon-folder-3");
			}
		}
	});

    /** event selectable menu to favorite menu
		** 1). event open properties window favorite
		** 2). event add to menu favorite
		** 3). event delete to menu favorite
		** 4). close properties favorite window
		** 5). event draggable and droppable add to menu favorite
		** 6). event copy link address
	*/
    // 1). event open properties window favorite
	$("body").off("contextmenu",".navbar-menu a:not([data-disabled]):not(:contains('Dashboard'))");
	$("body").on("contextmenu",".navbar-menu a:not([data-disabled]):not(:contains('Dashboard'))", function(e) {
		var _this = $(this);
		if(is.screen !== "mobile_version") {
			// turn on properties only on dekstop / tablet version
			e.preventDefault();
		}

		if(_this.attr("data-type") == "favorite") {
			$("#addto_favorite").closest("li").hide();
			$("#delete_favorite").closest("li").show();
		} else {
			$("#addto_favorite").closest("li").show();
			$("#delete_favorite").closest("li").hide();
		}

		if(e.which === KEY.CLICKRIGHT || e.keyCode === KEY.CLICKRIGHT) {
			$(".navbar-properties").show();
			properties = true;
			// define properties position
			$(".navbar-properties").css({left: _this.offset().left + _this.outerWidth()-15});
			var _scroll = _this.offset().top - $(window).scrollTop();
			if(_scroll < 540) {
				$(".navbar-properties").css({top: _scroll});
			} else {
				$(".navbar-properties").css({top: _scroll - $(".navbar-properties").outerHeight()});
			}

			// manipulate attributes
			$("#addto_favorite")
				.attr("data-menu", _this.attr("data-menu"))
				.attr("data-slug", _this.attr("data-slug"));
			$("#delete_favorite")
				.attr("data-menu", _this.attr("data-menu"))
				.attr("data-key", _this.closest("li").attr("data-key"));
			$("#open_newtab").attr("href", _this.attr("href"));
			$("#copylink_address").attr("data-copy", _this.attr("href"));
		}
	});
    // 2). event add to menu favorite
	$("body").off("click","#addto_favorite").on("click","#addto_favorite",function(e) {
		e.preventDefault();
		var _this = $(this),
			desc = parsing.toUpper(_this.attr("data-slug").replace(/\-/g, " "), 2);
		$.ajax({
			type: "POST",
			url: location.pathname+"?r="+_this.attr("data-href"),
			data: {
				"Favorite[menu_id]": _this.attr("data-menu"),
				"Favorite[type]": "favorite",
			},
			dataType: "text",
			error: function(xhr, errors, message) {
				console.log(xhr, errors, message);
			},
			beforeSend: function() {
				navigasi.process("load");
			},
			success: function(data) {
				if(!$.trim(data)) {
					notification.open("danger", "Menu "+ desc +" sudah ada di Favorite.", 2000);
				} else {
					$(".navbar-favorite-body").html(data);
					notification.open("success", "Menu "+ desc +" berhasil ditambah ke Favorite.", 2000);
				}
			},
			complete: function() {
				navigasi.process("destroy");
			}
		});
	});
    // 3). event delete to menu favorite
	$("body").off("click","#delete_favorite").on("click","#delete_favorite", function(e) {
		e.preventDefault();
		var _this = $(this);
		$.ajax({
			type: "POST",
			url: location.pathname+"?r="+_this.attr("data-href"),
			data: {
				key: _this.attr("data-key"),
			},
			dataType: "text",
			error: function(xhr, errors, message) {
				console.log(xhr, errors, message);
			},
			beforeSend: function() {
				navigasi.process("load");
			},
			success: function(data) {
				$(".navbar-favorite-body").html(data);
			},
			complete: function() {
				navigasi.process("destroy");
			}
		});
	});
    // 4). close properties favorite window
	$("body").off("click","#close_properties").on("click","#close_properties", function(e) {
		e.preventDefault();
		$(".navbar-properties").hide();
	});
    // 5). event draggable and droppable add to menu favorite
	$(".navbar-menu").draggable({
		appendTo: ".navbar-favorite-body ul",
		axis: "y",
		containment: ".navbar-left",
		handle: "a:not([data-disabled]):not([data-type='favorite']):not(:contains('Dashboard'))",
		helper: "clone",
		start: function(ev, ui) {
			$("body").prepend("<div class=\"ondrag\"></div>");
			var _this = $(this);
			_this.find("a").attr("data-href","favorite/create");
		},
		stop: function(ev, ui) {
			$(".ondrag").remove();
		}
	});
	$(".navbar-favorite-container .navbar-favorite-body").droppable({
		drop: function(ev, ui) {
			var _this = $(ui.draggable),
				desc = parsing.toUpper(_this.find("a").attr("data-slug").replace(/\-/g, " "), 2);

			$.ajax({
				type: "POST",
				url: location.pathname+"?r="+_this.find("a").attr("data-href"),
				data: {
					"Favorite[menu_id]": _this.find("a").attr("data-menu"),
					"Favorite[type]": "favorite",
				},
				dataType: "text",
				error: function(xhr, errors, message) {
					console.log(xhr, errors, message);
				},
				beforeSend: function() {
					navigasi.process("load");
				},
				success: function(data) {
					if(!$.trim(data)) {
						notification.open("danger", "Menu "+ desc +" sudah ada di Favorite.", 2000);
					} else {
					$(".navbar-favorite-body").html(data);
						notification.open("success", "Menu "+ desc +" berhasil ditambah ke Favorite.", 2000);
					}
				},
				complete: function() {
					navigasi.process("destroy");
					_this.find("a").removeAttr("data-href");
				}
			});
		}
	});
    // 6). event copy link address
	$("body").off("click","#copylink_address").on("click","#copylink_address", function(e) {
		e.preventDefault();
		var _this=$(this),
			_temp = $("<input>");

		// append to body
		$("body").append(_temp);
		_temp.val(location.host + _this.attr("data-copy")).select();
		document.execCommand("copy");
		// langsung di remove setelah execCommand = true
		_temp.remove();
	});
    /** end event selectable menu to favorite menu  */

    /** resizable menu container */
	// handles { n: top center, e: right center, s: bottom center, w: left center }
	// handles { ne: top right, se: bottom right, sw: bottom left, nw: top left }
    $(".navbar-favorite-container").resizable({
		handles: "n",
		minHeight: 220,
		maxHeight: 430,
		create: function(ev, ui) {
			$(".ui-resizable-handle.ui-resizable-n").append("<span>==</span>");
		},
		resize: function(ev, ui) {
			var _currentHeight = ui.size.height,
				_this=$(this);
			_this.height(_currentHeight);
		},
		stop: function(ev, ui) {
			var _currentTop = parseInt($(".navbar-favorite-container").css("top")),
				_currentNavbarMenuHeight = $(".navbar-menu-container").outerHeight() + _currentTop,
				_currentNavbarMenuBodyHeight = $(".navbar-menu-body").outerHeight() + _currentTop,
				_this=$(this);

			_this.css({top:0});
			$(".navbar-menu-container").css({height:_currentNavbarMenuHeight});
			$(".navbar-menu-body").css({height:_currentNavbarMenuBodyHeight});
			// save position height
			$.ajax({
				url: location.pathname+"?r=favorite/position",
				type: "POST",
				dataType: "text",
				data: {
					"Favorite[size]": ui.size.height+'~'+_currentNavbarMenuHeight+'~'+_currentNavbarMenuBodyHeight,
					"Favorite[type]": "size",
				},
				beforeSend: function() {},
				success: function(data) {},
				complete: function() {},
				error: function(xhr, errors, message) {
					console.log(xhr, errors, message);
				},
			});
		}
	});
	/** end resizable menu container */

    /** event force uppercase text */
	$("body").off("keyup","input[type=\"text\"]:not([data-search]):not([data-plugin-inputmask]):not([not-uppercase]), textarea:not([not-uppercase])");
	$("body").on("keyup","input[type=\"text\"]:not([data-search]):not([data-plugin-inputmask]):not([not-uppercase]), textarea:not([not-uppercase])", function(e) {
		e.preventDefault();
		$(this).val($(this).val().toUpperCase());
	});
	/** end event force uppercase text */
});