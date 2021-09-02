/**
	** Project JavaScript Widget Module
	** @author Triwahyu Pamungkas Pribadi
	** @create on January, 5 2018
	** @update on April, 5 2019
**/

$(document).ready(function() {
	/* init checkbox input */
	checkbox.init();
	/* init dropdown select */
	dropdown.init();
	/* init input text */
	input.init();
	/* init radio button */
	radiobox.init();
	/* init tooltip */
	tooltip.init();
	/* events to back to scrollTop = 0 */
	backtotop.option("disabled");
	/* bypass script injection */
	hack.ghostHunter();
	/* default enterKey is disabled */
	enterKey.option("disabled");
	/* default inputKey is disabled */
	inputKey.option("disabled");
	/* init checker */
	checker.init();
	/* print status screen */
	mode.init();
}); 

/**
	** Function yang menggunakan prototype simpan disini
*/
(function( $ ) {
	"use strict";
	/**	effectss function
		** @kindOfAnimationType
		** [ bounce, pulse, swing, hinge ]
		** [ bounceIn, bounceInUp, bounceInDown, bounceInLeft, bounceInRight ]
		** [ bounceOut, bounceOutUp, bounceOutDown, bounceOutLeft, bounceOutRight ]
		** [ fadeIn, fadeInUp, fadeInDown, fadeInLeft, fadeInRight ]
		** [ fadeOut, fadeOutUp, fadeOutDown, fadeOutLeft, fadeOutRight ]
		** [ flip, flipInY, flipInX, flipOutY, flipOutX, lightSpeedIn, lightSpeedOut ]
		** [ rotateIn, rotateInUpLeft , rotateInUpRight, rotateInDownLeft, rotateInDownRight ]
		** [ rotateOut, rotateOutUpLeft , rotateOutUpRight, rotateOutDownLeft, rotateOutDownRight ]
		** [ slideInUp, slideInDown, slideInLeft, slideInRight, slideOutUp, slideOutDown, slideOutLeft, slideOutRight ]
		** [ zoomIn, zoomInUp, zoomInDown, zoomInLeft, zoomInRight ]
		** [ zoomOut, zoomOutUp, zoomOutDown, zoomOutLeft, zoomOutRight ]
	*/
	$.fn.effects = function(animate, callback) {
		var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
		this.addClass("animation " +animate).one(animationEnd, function() {
			$(this).removeClass("animation " +animate);
			if(callback) callback();
		});
		return this;
	};
	// refrence from: https://daneden.github.io/animate.css/
	/** End effectss function */
	
	/**	Loader function */
	$.fn.loader = function(params, opt) {
		if(params === "load") loader.render(this);
		if(params === "destroy") loader.destroy();
		
		var _css = {
			left: this.position().left + this.outerWidth(),
			top: this.position().top,
		}, setting = $.extend({}, loader.config, opt), _this=this;
		
		$(".loader-container").css({ left:  _css.left + 'px', top:  _css.top + 'px' });
		return this.next(".loader-container").each(function() {
			$(this).css({
				height: setting.height,
				left: _css.left + setting.left,
				top: _css.top + setting.top,
				width: setting.width,
			});
		});
	};
	/** End Loader function  */
	
	/**	Table Loader function */
	$.fn.tableLoader = function(params, opt) {
		if(params === "load") tableLoader.render(this);
		if(params === "destroy") tableLoader.destroy();
		
		var _css = {
			height: this.outerHeight(),
			width: this.outerWidth(),
		}, setting = $.extend({}, tableLoader.config, opt);
		
		$(".table-loader-container").css({
			opacity: .6,
			height: _css.height +'px',
			width: _css.width +'px',
			display: 'inline-block',
			position: 'absolute',
			backgroundColor: '#eee',
			zIndex: 1,
		});
		
		$(".loader-container").css({
			height: '60px',
			width: '60px',
			transform: 'translate(-50%, -50%)',
			left: '50%',
			top: '50%',
		});
	}
	/**	End Table Loader function */
	
	/**	Popup function */
	$.fn.popup = function(params, opt) {
		if(params === "open") modal.open();
		if(params === "close") popup.close();
		
		var setting = $.extend({}, modal.config, opt);
		// render content
		if(typeof setting.container !== "undefined") {
			var $body = $("[data-popup='"+setting.container+"']").html();
			
			$(".popup-body").html($body);
			$(".popup-form").effects("fadeIn");
			$(".popup-form").attr("data-id", setting.container);
			// biar attribute name dan id gak double
			if(is.popup === true) {
				$("[data-popup='"+setting.container+"']").empty();
			}
			// event click close popup form
			$(document).on("click","#btn-remove", function(e) {
				e.preventDefault();
				$("[data-id='"+setting.container+"']").effects("fadeOut", function() {
					is.popup = false;
					// kembalikan [data-popup=\"setting.container\"] setelah di empty
					$(".popup-body").find("fieldset >").unwrap(); // unwrap fieldset kalau ada
					$(".popup-body").find("legend").remove(); // remove legend kalau ada
					var $body = $(".popup-body").html();
					$("[data-popup='"+setting.container+"']").html($body);
					// close popup
					popup.close();
				});
			});
			// close popup form with escape
			$(document).keydown(function(e) {
				if(e.keyCode == KEY.ESCAPE) $("#btn-remove").trigger("click");
			});
		}
		// jika fieldset: true
		if(setting.fieldset === true) {
			$(".popup-body").wrapInner("<fieldset></fieldset>");
			$("fieldset").prepend("<legend></legend>");
		}
		// jika draggable: true
		if(setting.draggable === true) $(".popup-form").drag();
		// jika event checkbox: true
		if(setting.initEvent.checkbox === true) checkbox.event();
		// jika event radiobox: true
		if(setting.initEvent.radiobox === true) radiobox.event();
		// jika enterKey: true
		if(setting.enterKey === true) enterKey.option("enable");
		// jika inputKey: true
		if(setting.inputKey === true) inputKey.option("enable");
		
		return $.each(this, function() {
			var $form = $("[data-popup=\"form\"]");
			
			title: $form.find("h5").text(setting.title);
			fieldset: setting.fieldset;
			fieldsetOptions: {
				$form.find("legend").css({
					color: setting.fieldsetOptions.colorText,
					fontSize: setting.fieldsetOptions.fontSize,
				});
				$form.find("fieldset").css({
					borderColor: setting.fieldsetOptions.borderColor,
				});
				title: $form.find("legend").text(setting.fieldsetOptions.title);
			};
			styleOptions: {
				$form.css({
					width: setting.styleOptions.width,
				});
			};
		});
	};
	/** End Popup function */
	
	/**	Drag function */
	$.fn.drag = function(opt) {
		opt = $.extend({}, draggable.config, opt);
		var _this = null,
			el = (opt.handle === "") ? this : this.find(opt.handle);
			
		el.on("mousedown", function(e) {
			var target = $(event.target);
			if(opt.handle === "") {
				_this = $(this);
				_this.addClass(opt.draggableClass);
			} else {
				_this = $(this).parent();
				_this.addClass(opt.draggableClass);
			}
			
			var posY = _this.offset().top + _this.outerHeight() - e.pageY,
				posX = _this.offset().left + _this.outerWidth() - e.pageX;

			if(target.prop("nodeName") !== "INPUT" && target.prop("nodeName") !== "SELECT") {
				$(document).on("mousemove", function(e) {
					_this.offset({
						top: e.pageY + posY - _this.outerHeight(),
						left: e.pageX + posX - _this.outerWidth()
					});
				}).on("mouseup", function() {
					$(this).off("mousemove");
					if(_this !== null) {
						_this.removeClass(opt.draggableClass);
						_this = null;
					}
				});
				e.preventDefault();
			}
		}).on("mouseup", function() {
			if(opt.handle === "") {
				_this.removeClass(opt.draggableClass);
			} else {
				_this.removeClass(opt.draggableClass);
			}
			_this = null;
		});
		return this;
	};
	/** End drag function */
}( jQuery ));

/** Define flag for particular purpose */
var is = {
	popup: false, // kalau tidak ada popup modal maka false
	screen: "dekstop_version", // cek target is mobile, tablet or dekstop
	timeOut: 0, // set interval value untuk event touchstart touchend
	trigger: 70, // default value untuk cek position top bottom
	upload: false, // status reader is false
};

/**
	** @description
	** waitForFinalEvent digunakan untuk memanggil fungsi apabila resize window / maximize minimize window
*/
var waitForFinalEvent = (function () {
	var timers = {};
	return function (callback, ms, uniqueId) {
		if(!uniqueId)
			uniqueId = "c0r3-pr0j3ct-c0nf1g";
		if(timers[uniqueId])
			clearTimeout (timers[uniqueId]);
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();
/** End waitForFinalEvent function */

/**
	** @description
	** Untuk mengetahui status screen is mobile / tablet / dekstop
*/
var mode = {
	init: function() {
		mode.screen();
		// if window resize
		mode.onResize();
	},
	screen: function() {
		var w = $(window).width();
		if(w > 319 && w < 767) {
			is.screen = "mobile_version";
		} else if(w > 768 && w < 1023) {
			is.screen = "tablet_version";
		} else if(w > 1024) {
			is.screen = "dekstop_version";
		}
	},
	onResize: function() {
		$(window).resize(function() {
			waitForFinalEvent(function() {
				mode.screen();
			}, 300, "g3t5t4tu5-scr33n");
		});
	}
}
/** End status function */

/**
	** @description
	** Merupakan kumpulan fungsi untuk bypass script injection, block dll.
	** @example speedy script injection, adblock dll
*/
var hack = {
	ghostHunter: function() {
		/* Bypass speedy script injection */
		$("[id^=beacon]").each(function() {
			$(this).remove();
		});
		$("script").each(function() {
			if(this.src.substring(0,16) === "http://cfs.uzone") {
				$(this).remove();
			}
			if(this.text.substring(0,14) === "if (self==top)") {
				$(this).remove();
			}
		});
		/* End bypass speedy script injection */
	}
};
/** End hack function */

/**
	** @description
	** parsing.toUpper(string, 1), digunakan untuk uppercase text character pertama pada kata pertama
	** @example hello world == Hello world
	** parsing.toUpper(string, 2), digunakan untuk uppercase text character pertama untuk semua kata
	** @example hello world == Hello World
	** parsing.toUpper(string, 3), digunakan untuk uppercase semua kata
	** @example hello world == HELLO WORLD
	** parsing.toLower(string), merubah text uppercase to lowercase
	** parsing.toSlug(string), merubah kalimat menjadi slug text
	** @example Cara membuat halaman menjadi cepat == cara-membuat-halaman-menjadi-cepat
*/
var parsing = {
	toUpper: function(str, type) {
		/** (1). First word, (2). First after space , (3). All word */
		var regex;
		if(typeof type === "undefined") type = 1;
		if(type == 1) regex = /(\b)([a-zA-Z])/;
		else if(type == 2) regex = /(\b)([a-zA-Z])/g;
		else if(type == 3) regex = /([a-zA-Z])/g;
		
		return str.replace(regex, function(e) {
			return e.toUpperCase();
		});
	},
	toLower: function(str) {
		return str.replace(/([a-zA-Z])/g, function(e) {
			return e.toLowerCase();
		});
	},
	toSlug: function(str, i) {
		var and = ["and", "dan"];
		if(typeof i === "undefined")
			i = and[1];
		return str.toLowerCase()
			// replace space and replace multiline (-)
			.replace(/\s+/g, "-").replace(/\-\-+/g, "-")
			// replace (&) to (-and- or -dan- ) and all non-word character
			.replace(/&/g, and[i]).replace(/[^\w\-]+/g, "")
			// trim (-) awal dan akhir character
			.replace(/^-+/, "").replace(/-+$/, "");
	},
	toComma: function(str) {
		return (str != null) ? str.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") : 0;
	},
	toInt: function(str) {
		return (str != null) ? str.toString().replace(/\,/g, "") : 0;
	}
};
/** End parsing function */

/**
	** @description
	** Digunakan untuk pindah dari satu field ke field lain dengan menekan enter
	** enterKey.option("enable"), untuk mengaktifkan enterKey
*/
var enterKey = {
	option: function(opt) {
		if(opt === "enable") {
			enterKey.event();
		}
	},
	event: function() {
		$("body").off("keydown","[tabsort]").on("keydown","[tabsort]", function(e){
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0,
				tabval = $(this).attr("tabsort"),
				target = $(e.target).prop("nodeName");
			if(key == KEY.ENTER){
				var element = $('[tabsort="'+(parseInt(tabval) +1)+'"]'),
					ntarget = element.prop("nodeName");
				element.focus();
				if(ntarget == "SELECT") {
					// ini kalau pakai select2 nya kartik
					if(element.hasClass("select2-hidden-accessible")) {
						element.select2("open");
						// setelah milih, langsung focus ke element tab-sort berikutnya
						element.on("select2:select", function() {
							var tabval = $(this).attr("tabsort"),
								element = $('[tabsort="'+(parseInt(tabval) +1)+'"]');
							element.focus();
						});
					}
				}
				
				// biar g nge submit pada saat di enter
				if(target !== "BUTTON") {
					e.preventDefault();
					return false;
				}
			}
			
			// SAVE
			if(e.shiftKey && (key == KEY.CTRL)){
				$("button[type=\"submit\"]").trigger("click");
			}
		});
	},
	keyback: function() {
		$("body").off("keydown","[tabsort]").on("keydown","[tabsort]", function(e){
			var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0,
				tabval = $(this).attr("tabsort"),
				target = $(e.target).prop("nodeName");
			if(key == KEY.LEFT){
				var element = $('[tabsort="'+(parseInt(tabval) -1)+'"]'),
					ntarget = element.prop("nodeName");
				element.focus();
				if(ntarget == "SELECT") {
					// ini kalau pakai select2 nya kartik
					if(element.hasClass("select2-hidden-accessible")) {
						element.select2("open");
						// setelah milih, langsung focus ke element tab-sort berikutnya
						element.on("select2:select", function() {
							var tabval = $(this).attr("tabsort"),
								element = $('[tabsort="'+(parseInt(tabval) -1)+'"]');
							element.focus();
						});
					}
				}
			}
		});
	}
};
/** End enterKey function */

/**
	** @description
	** Digunakan untuk pindah dari satu field ke field lain dengan cara menambahkan maxlength pada attribut input text
	** inputKey.option("enable"), untuk mengaktifkan inputKey
*/
var inputKey = {
	option: function(opt) {
		if(opt === "enable") {
			inputKey.event();
		}
	},
	event: function() {
		$.each($("input[maxlength]"), function(i, e) {
			$(this).attr("tabindex", parseInt(i+1));
			$("body").off("input","input[maxlength]").on("input","input[maxlength]", function(e) {
				e.preventDefault();
				var tab = $("input[tabindex]"),
					el = $(this),
					len = el.attr("maxlength");
					
				for(var i = 0; i < tab.length; i++) {
					if(tab[i] == this) {
						while((tab[i]) == (tab[i+1])) i++;
						if((i+1) < tab.length) {
							if(el.val().length == len) $(tab[i+1]).focus();
						}
					}
				}
			});
		});
	}
};
/** End inputKey function */

/**
	** @description
	** checker.email(selector), mandatory email text
	** checker.numberOnly(), mandatory angka pada input text
	** @example <input type="text" number-only="true">, untuk mengaktifkan
	** checker.upperText(), untuk uppercase text pada input text
	** @example <input type="text" uppercase="true">, untuk mengaktifkan
*/
var checker = {
	init: function() {
		/* init number only field */
		checker.numberOnly();
		/* force input text with uppercase */
		// checker.upperText();
	},
	// validasi email format
	email: function(e) {
		var a = e.val().indexOf("@"),
			b = e.val().lastIndexOf(".");
		return a < 1 || b < a+2 || b+2 > e.val().length;
	},
	numberOnly: function() {
		$.each($("[number-only"), function(i, e) {
			var _attr = $(e).attr("number-only");
			if(_attr == "true")
				checker.event("number only", e);
			else if(_attr == "decimal")
				checker.event("decimal point", e);
		});
	},
	event: function(type, el) {
		switch(type) {
			case "number only":
				$(el).keypress(function(e) {
					var key = e.keyCode || e.which;
					if(key != 8 && key != 0 && (key < 48 || key > 57))
						return false;
				});
			break;
			case "decimal point":
				$(el).keypress(function(e) {
					var key = e.keyCode || e.which;
					if(key != 8 && key != 0 && (key < 48 || key > 57) && (key != 44 || $(this).val().indexOf(",") != -1))
						return false;
				});
			break;
		}
	},
	upperText: function() {
		$.each($("input[type=\"text\"], textarea"), function() {
			if($(this).attr("uppertext") == "true") {
				$(this).css("text-transform","uppercase");
			}
		});
	},
	// navigator for detect browser
	browserType: function() {
		var _browser;
		if((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf("OPR")) != -1)
			_browser = "Opera";
		else if(navigator.userAgent.indexOf("Chrome") != -1)
			_browser = "Chrome";
		else if(navigator.userAgent.indexOf("Safari") != -1)
			_browser = "Safari";
		else if(navigator.userAgent.indexOf("Firefox") != -1)
			_browser = "Firefox";
		else
			_browser = "Uknown";
		
		return _browser;
	},
};
/** End checker function */

/**
	** @description
	** Define key codes to help with handling keyboard events.
*/
const KEY = {
	CLICKRIGHT: 3,
	TAB: 9,
	ENTER: 13,
	SHIFT: 16,
	CTRL: 17,
	ESCAPE: 27,
	SPACE: 32,
	PAGE_UP: 33,
	PAGE_DOWN: 34,
	END: 35,
	HOME: 36,
	LEFT: 37,
	UP: 38,
	RIGHT: 39,
	DOWN: 40,
	DELETE: 91,
	PLUS: 107,
	MINUS: 109,
	F1: 112, F2: 113, F3: 114, F4: 115, F5: 116, F6: 117, F7: 118, F8: 119, F9: 120, F10: 121, F11: 122, F12: 123,
	ALT: 18,
};
/** End key function  */

/**
	** function object to array
	** @description
	** fungsi ini digunakan untuk merubah object ke array
*/
var ObjectToArray = function(obj) {
	var property_id = Object.getOwnPropertyNames(obj),
		property_values = Object.values(obj),
		_construct = function(val) {
			this.property_id = [];
			this.property_values = [];
			this.length = val;
		},
		_class = new _construct(property_id.length);

	for(var i = 0; i < property_id.length; i++) {
		_class.property_id[i] = property_id[i];
		_class.property_values[i] = property_values[i];
	}
	return _class;
}
/** End function object to array */

/**
	** function backtotop
	** @description
	** Untuk kembali ke posisi scrollTop = 0
	** backtotop.option("enable");
*/
var backtotop = {
	option: function(opt) {
		if(opt === "enable") {
			// render layout
			backtotop.render();
			// init event
			backtotop.event();
		}
	},
	open: function() {
		var scrollTop = $(window).scrollTop();
		if(scrollTop > backtotop.trigger) {
			$(".backtotop").removeClass("hidden");
		} else {
			$(".backtotop").removeClass("hidden").addClass("hidden");
		}
	},
	trigger: 100,
	render: function() {
		$container = $([
			'<div class="backtotop hidden">',
				'<div class="backtotop-container">',
					'<i class="fontello icon-angle-double-up"></i>',
				'</div>',
			'</div>'
		].join(''));
		$container.appendTo("body");
		backtotop.event();
	},
	event: function() {
		backtotop.open();
		// when scrolling and scrollTop > 100, show backtotop
		$(window).on("scroll", function() {
			backtotop.open();
		});
		// event click backtotop
		$("body").off("click",".backtotop-container").on("click",".backtotop-container", function(e) {
			e.preventDefault();
			$("html, body").animate({scrollTop:0}, 600);
		});
	}
};
/** End backtotop function */

/**
	** function notification
	** @description
	** notification.open(type, string, timeout) / notification.open(type, string)
	** @example
	** notification.open('success', 'Data master berhasil disimpan', 3000);
*/
var notification = {
	init: function() {
		notification.event();
	},
	close: function(timeout) {
		if(typeof timeout !== "undefined") {
			setTimeout(function() {
				$(".notification").fadeOut("slow", function() {
					$(this).remove();
				});
			}, timeout);
		} else {
			$(".notification").fadeOut("slow", function() {
				$(this).remove();
			});
		}
	},
	event: function() {
		$("body").off("click",".close").on("click",".close", function(e) {
			e.preventDefault();
			notification.close();
		});
	},
	open: function(type, text, timeout) {
		if(type == "info") {
			if(typeof text === "undefined") text = "Info !...";
			notification.render("info", text);
		} else if(type == "danger") {
			if(typeof text === "undefined") text = "Danger !...";
			notification.render("danger", text);
		} else if(type == "success") {
			if(typeof text === "undefined") text = "Success !...";
			notification.render("success", text);
		} else if(type == "warning") {
			if(typeof text === "undefined") text = "Warning !...";
			notification.render("warning", text);
		}
		
		notification.init();
		if(typeof timeout !== "undefined") notification.close(timeout);
	},
	render: function(type, text) {
		var icons = "";
		if(type == "success") icons = "icon-ok-circle";
		else if(type == "danger") icons = "icon-minus-circle";
		else if(type == "info") icons = "icon-info-circled";
		else if(type == "warning") icons = "icon-cancel-circle";
		
		$container = $([
			'<div class="notification '+type+'">',
				'<span><i class="fontello white '+icons+'"></i>'+text+'</span>',
				'<a href="javascript:void(0)" class="close"><i class="fontello dark-blue icon-cancel-2"></i></a>',
			'</div>'
		].join(''));
		
		$(".notification").remove();
		$container.appendTo("body");
		
		$(".notification > a").css({
			height: parseInt($(".notification").outerHeight()) +'px',
			// width: parseInt($(".notification").outerHeight()) +'px',
		});
		// $(".notification").hide().fadeIn();
	}
};
/** End function notification */

/**
	** function window popup
	** @description
	** 1). Alert
	** @example
	** popup.open('alert', { message: 'Pilih salah satu data!' });
	** @params title, message
	** 2). Confirm
	** @example
	** popup.open('confirm', { message: 'Apakah anda yakin ingin menghapus data ini!' }, function() { if ok, do something here ... });
	** @params title, message
*/
var popup = {
	init: function() {
		popup.event();
	},
	event: function() {
		// events click remove popup container
		$("body").off("click","#btn_remove, #btn_ok, #btn_cancel").on("click","#btn_remove, #btn_ok, #btn_cancel", function(e) {
			e.preventDefault();
			$(".popup-mini").effects("fadeOut", function() {
				popup.close();
			});
		});
		// close popup with escape
		$(document).keydown(function(e) {
			if(e.keyCode == KEY.ESCAPE) {
				$("#btn_remove").trigger("click");
			}
		});
	},
	close: function() {
		$(".popup").remove();
	},
	config: {},
	open: function(type, opt) {
		opt = $.extend({}, popup.config, opt);
		
		if(type == "alert" && typeof opt.title === "undefined") opt.title = "Peringatan!";
		else if(type == "confirm" && typeof opt.title === "undefined") opt.title = "Konfirmasi!";
		
		popup.layout();
		popup.render(type, opt.message, opt.title, opt.selector, opt.target);
		popup.init();
		$(".popup-mini").drag();
	},
	render: function(type, desc, title, selector, target) {
		$container = $([
			'<div class="popup-mini">',
				'<div class="popup-header">',
					'<h5>'+title+'</h5>',
					'<a href="javascript:void(0)" class="popup-remove" id="btn_remove">',
						'<i class="fontello gray icon-cancel-2"></i>',
					'</a>',
				'</div>',
				'<div class="popup-body"><p>'+desc+'</p></div>',
			'</div>'
		].join(''));
		$container.appendTo(".popup");
		
		if(type == "confirm") {
			$(".popup-mini")
				.append(
					"<a href=\"javascript:void(0)\" class=\"popup-btn\" id=\"btn_cancel\"><span>Tidak</span></a>",
					"<a href=\"javascript:void(0)\" class=\"popup-btn ml\" id=\""+selector+"\" data-target=\""+target+"\"><span>OK</span></a>");
		} else if("alert") {
			$(".popup-mini").append("<a href=\"javascript:void(0)\" class=\"popup-btn\" id=\"btn_ok\"><span>OK</span></a>");
		}
		$(".popup-mini").effects("fadeIn");
	},
	layout: function() {
		$container = $([
			'<div class="popup">',
				'<div class="popup-wrapper"></div>',
			'</div>'
		].join(''));
		$container.appendTo("body");
	}
};
/** End popup window function  */

/**
	** function loading
	** @description
	** 1). loading.open("loading text")
	** 2). loading.open("loading circle")
	** 3). loading.open("loading bars")
	** @default
	** loading.open() == loading.open("loading bars")
	** loading.close(), untuk meremove loading
*/
var loading = {
	close: function() {
		$(".loading").remove();
	},
	open: function(type) {
		loading.layout();
		loading.render(type);
		if(typeof type === "undefined") loading.render("loading bars");
	},
	render: function(type) {
		if(typeof type === "undefined") var _split = "bars";
		else var _split = type.split(" ");
		
		var element = "";
		if(_split[1] == "text") {
			var arr = ["L","o","a","d","i","n","g"];
			element += "<div class=\""+_split[0]+"-container\">";
			for(var i = 0; i <=arr.length-1; i++) {
				element += "<div class=\""+_split[0]+"-"+_split[1]+" "+_split[0]+"-"+( i+1 )+"\">"+arr[i]+"</div>";
			}
			element += "</div>";
		} else {
			element += "<div class=\"float-"+_split[1]+"-container\">";
			element += "<div class=\"float-"+_split[1]+"-scale\">";
			for(var i = 1; i <= 8; i++) {
				element += "<div class=\"float-"+_split[1]+" float-"+_split[1]+"-"+i+"\"></div>";
			}
			element += "</div>";
			element += "<div class=\"float-"+_split[1]+"-text\">Loading ...</div>";
			element += "</div>";
		}
		$(element).appendTo("."+_split[0]);
	},
	layout: function() {
		$container = $([
			'<div class="loading">',
				'<div class="loading-layer"></div>',
			'</div>'
		].join(''));
		$container.appendTo("body");
	}
};
/** End loading function  */

/**
	** function checkbox input
	** @description
	** 1). Type Burger
	** @example <input type="checkbox" data-type="burger" data-align="left" data-title="title">
	** 2). Type Pizza
	** @example <input type="checkbox" data-type="pizza" data-align="left" data-title="title">
	** 3). Type Kebab [switch button]
	** @example <input type="checkbox" data-type="kebab">
*/
var checkbox = {
	init: function() {
		checkbox.render();
	},
	reset: function() {
		$.each($("input[type=\"checkbox\"]"), function() {
			var $type = $(this).attr("data-type");
			$(this).prop("checked", 0);
			if($type == "kebab") {
				$(".checklist-container").removeClass("checklist-on").addClass("checklist-off");
				$(".switch").removeClass("switch-on").addClass("switch-off");
				$(this).val("OFF");
			} else {
				$(".checklist-container > i").removeClass("icon-ok");
			}
		});
	},
	render: function() {
		$.each($("input[type=\"checkbox\"]"), function() {
			if(typeof $(this).attr("data-type") !== "undefined") {
				if($(this).parents(".checkbox-container").length < 1) {
					$(this).wrap("<div class=\"checkbox-container\" />");
					$(this).addClass("hidden");
					switch($(this).attr("data-type")) {
						case "kebab":
							$(this).after("<div class=\"checklist-container checklist-off\"><label>OFF</label><div class=\"switch switch-off\"></div></div>");
							$(this).nextAll().wrapAll("<div class=\"checkbox-wrapper\" />");
							$(this).val("OFF");
						break;
						default:
							$(this).after("<label for=\""+$(this).attr("id")+"\">"+$(this).attr("data-title")+"</label>");
							$(this).after("<div class=\"checklist-container\"><i class=\"fontello gray\"></i></div>");
							$(this).nextAll().wrapAll("<div class=\"checkbox-wrapper\" />");
						break;
					}
				}
				
				if($(this).prop("disabled") == true) {
					$(this).next().find(".checklist-container").removeClass("disabled").addClass("disabled");
					$(this).next().find("label").removeClass("disabled").addClass("disabled");
				}
			}
		});
		checkbox.event();
	},
	event: function() {
		$.each($("input[type=\"checkbox\"]"), function() {
			var $this = $(this),
				$next = $(this).next(".checkbox-wrapper"),
				$parents = $(this).parents(".checkbox-container");
			
			// setting position label
			if($(this).attr("data-align") == "left") {
				$next.find("label").addClass("data-left");
				$next.find(".checklist-container").addClass("data-left");
			} else if($(this).attr("data-align") == "right") {
				$next.find("label").addClass("data-right");
				$next.find(".checklist-container").addClass("data-right");
			}
			// setting position checkbox container to horizontal or vertical
			if($(this).attr("data-parallel") == "horizontal") {
				$parents.addClass("data-horizontal");
			} else if($(this).attr("data-parallel") == "vertical") {
				$parents.addClass("data-vertical");
			}
			// events click
			switch($(this).attr("data-type")) {
				case "kebab":
					if($(this).prop("checked") === true || $(this).prop("checked") === 1) {
						$parents.find(".checklist-container").removeClass("checklist-off").addClass("checklist-on");
						$parents.find(".switch").removeClass("switch-off").addClass("switch-on");
						$parents.find("label").html("ON")
					} else {
						$parents.find(".checklist-container").removeClass("checklist-on").addClass("checklist-off");
						$parents.find(".switch").removeClass("switch-on").addClass("switch-off");
						$parents.find("label").html("OFF")
					}
					
					$parents.find(".switch").click(function() {
						$(this).toggleClass("switch-on");
						if($(this).hasClass("switch-on")) {
							$(this).removeClass("switch-off");
							$(this).parents(".checklist-container").removeClass("checklist-off").addClass("checklist-on");
							$(this).prev().text("ON");
							$this.prop("checked", 1);
							$this.val("ON");
						} else {
							$(this).removeClass("switch-on").addClass("switch-off");
							$(this).parents(".checklist-container").removeClass("checklist-on").addClass("checklist-off");
							$(this).prev().text("OFF");
							$this.prop("checked", 0);
							$this.val("OFF");
						}
					});
				break;
				default:
					if($(this).prop("checked") === true || $(this).prop("checked") === 1) {
						$parents.find(".checklist-container >").addClass("icon-ok");
					} else {
						$parents.find(".checklist-container >").removeClass("icon-ok");
					}
					
					$($(this)).click(function() {
						if($(this).prop("checked") == 1) {
							$(this).next().find(".fontello").addClass("icon-ok");
						} else {
							$(this).next().find(".fontello").removeClass("icon-ok");
						}
					});

					$parents.find(".checklist-container").click(function() {
						$this.trigger("click");
					});
				break;
			}
		});
	}
};
/** End function checkbox input */

/**
	** function radio button
	** @description
	** 1). Type Seblak
	** @example <input type="radio" data-type="seblak" data-align="left" data-title="title">
	** 2). Type Batagor
	** @example <input type="radio" data-type="batagor" data-align="left" data-title="title">
*/
var radiobox = {
	init: function() {
		radiobox.render();
	},
	reset: function() {
		$("input[type=\"radio\"]").prop("checked", 0);
		$(".radiobox-list").removeClass("active");
	},
	render: function() {
		$.each($("input[type=\"radio\"]"), function() {
			if(typeof $(this).attr("data-type") !== "undefined") {
				if($(this).parents(".radiobox-container").length < 1) {
					$(this).wrap("<div class=\"radiobox-container\" />");
					$(this).addClass("hidden");
					switch($(this).attr("data-type")) {
						default:
							$(this).after("<label for=\""+$(this).attr("id")+"\">"+$(this).attr("data-title")+"</label>");
							$(this).after("<div class=\"radiobox-list\"></div>");
							$(this).nextAll().wrapAll("<div class=\"radiobox-wrapper\" />");
						break;
					}
				}
			}
		});
		radiobox.event();
	},
	event: function() {
		$.each($("input[type=\"radio\"]"), function() {
			var $this = $(this),
				$next = $(this).next(".radiobox-wrapper"),
				$parents = $(this).parents(".radiobox-container");
			
			// setting position label
			if($(this).attr("data-align") == "left") {
				$next.find("label").addClass("data-left");
				$next.find(".radiobox-list").addClass("data-left");
			} else if($(this).attr("data-align") == "right") {
				$next.find("label").addClass("data-right");
				$next.find(".radiobox-list").addClass("data-right");
			}
			// setting position radiobox container to horizontal or vertical
			if($(this).attr("data-parallel") == "horizontal") {
				$parents.addClass("data-horizontal");
			} else if($(this).attr("data-parallel") == "vertical") {
				$parents.addClass("data-vertical");
			}
			// event click 
			switch($(this).attr("data-type")) {
				default:
					if($(this).prop("checked") === true || $(this).prop("checked") === 1) {
						$parents.find(".radiobox-list").addClass("active");
					} else {
						$parents.find(".radiobox-list").removeClass("active");
					}
					
					$($(this)).click(function() {
						$(this).next().find(".radiobox-list").removeClass("active").addClass("active");
						$.each($("input[type=\"radio\"]"), function(index, element) {
							if($(element).prop("checked") == 0) {
								$(element).next().find(".radiobox-list").removeClass("active");
							}
						});
					});
					
					$parents.find(".radiobox-list").click(function() {
						$this.trigger("click");
						$this.next().find(".radiobox-list").removeClass("active").addClass("active");
						$.each($("input[type=\"radio\"]"), function(index, element) {
							if($(element).prop("checked") == 0) {
								$(element).next().find(".radiobox-list").removeClass("active");
							}
						});
					});
				break;
			}
		});
	}
};
/** End function radio button */

/**
	** function dropdown select
	** @description
	** @example <select data-name="dropdown" data-search="true"></select>
*/
var dropdown = {
	init: function() {
		dropdown.render();
	},
	reset: function() {
		$(".dropdown-reset >").trigger("click");
	},
	render: function() {
		// container dropdown
		$.each($("[data-name=\"dropdown\"]"), function() {
			var $parents = $(this).parents(".dropdown-container");
			if($parents.length < 1) {
				$(this).wrap("<div class=\"dropdown-container\" />");
				$container = $([
					'<input type="text" class="form-control dropdown-value" id="'+$(this).attr("id")+'" name="'+$(this).attr("name")+'">',
					'<a href="javascript:void(0)" class="drop-toggle">',
						'<i class="fontello gray icon-down-dir-3"></i>',
					'</a>',
					'<ul class="drop-menu"></ul>'
				].join(''));
				$(this).after($container);
				$(this).attr("id","change_id").attr("name","change_name");
				// searching
				$find = $(this).parents(".dropdown-container").find(".drop-menu");
				if($(this).attr("data-search") == "true") {
					var $divSch = $([
						'<div class="search">',
							'<input type="text" class="form-control onsearch">',
							'<i class="fontello icon-search-3"></i>',
						'</div>',
						'<div class="opt"></div>'
					].join(''));
					$find.append($divSch);
				}
				// ambil option, append ke ul.drop-menu > div.opt > li
				var $parents = $(this).parents(".dropdown-container").find(".opt");
				$.each($(this).find("option"), function() {
					$divOpt = $(['<li><a href="javascript:void(0)" data-id="'+$(this).attr("data-id")+'" data-value="'+$(this).val()+'">'+$(this).val()+'<a></li>'].join(''));
					$parents.append($divOpt);
				});
				$(this).hide();
			}
		});
		// container selectbox
		$.each($("[data-name=\"selectbox\"]"), function() {
			var $parents = $(this).parents(".selectbox-container");
			if($parents.length < 1) {
				$(this).wrap("<div class=\"selectbox-container\" />");
				$container = $([
					'<div class="selectbox-content" data-value="'+$(this).val()+'"></div>',
					'<div class="selectbox-menu">',
						'<div class="selectbox-header">',
							'<span><i class="fontello icon-popup"></i></span>',
							'<p>Select Category</p>',
						'</div>',
						'<div class="selectbox-search">',
							'<input type="text" class="form-control search-input">',
							'<i class="fontello gray icon-search-3"></i>',
						'</div>',
						'<div class="selectbox-body">',
							'<ul></ul>',
						'</div>',
					'</div>'
				].join(''));
				$(this).after($container);
				
				// ambil option, append ke div.selectbox-body > ul
				var $find = $(this).siblings(".selectbox-menu").find(".selectbox-body > ul"),
					$name = $(this).attr("name");
				$.each($(this).find("option"), function() {
					var $id = parsing.toSlug(parsing.toLower($(this).val()));
					$divOp = $([
						'<li>',
							'<input type="radio" data-type="batagor" data-title="'+$(this).val()+'" id="'+$id+'" name="'+$name+'">',
						'<li>'
					].join(''));
					console.log($divOpt);
					$find.append($divOp);
				});
				$(this).hide();
			}
		});
		// init event
		dropdown.event();
	},
	event: function() {
		// event open dropdown menu
		$("body").off("click",".drop-toggle").on("click",".drop-toggle", function(e) {
			e.preventDefault();
			$(this).next().toggleClass("open");
			if($(this).next().hasClass("open")) {
				$(this).next().slideDown("fast");
				$(this).find(".fontello").removeClass("icon-down-dir-3").addClass("icon-up-dir-2");
				// netralkan option dalam keadaan semula
				dropdown.search($(".onsearch"));
			} else {
				$(this).next().slideUp("fast");
				$(this).find(".fontello").removeClass("icon-up-dir-2").addClass("icon-down-dir-3");
			}
		});
		// event selected option
		$("body").off("click",".drop-menu li").on("click",".drop-menu li", function(e) {
			e.preventDefault();
			$parents = $(this).parents(".dropdown-container");
			$parents.find("input:not(.onsearch)").val($(this).text());
			$parents.find("input:not(.onsearch)").attr("value", $(this).text());
			$parents.find(".drop-toggle > ").removeClass("icon-up-dir-2").addClass("icon-down-dir-3");
			$(this).closest(".drop-menu").slideUp().removeClass("open");
			
			if($parents.find(".dropdown-reset").length < 1) {
				$divReset = $([
					'<a href="javascript:void(0)" class="dropdown-reset">',
						'<i class="fontello gray icon-cancel-4"></i>',
					'</a>'
				].join(''));
				$parents.find(".drop-toggle").before($divReset);
			}
			$("input.onsearch").val("");
		});
		// event reset value
		$("body").off("click",".dropdown-reset >").on("click",".dropdown-reset >", function(e) {
			e.preventDefault();
			$(this).parent().prev("input:not(.onsearch)").val("");
			$(this).parent().remove();
		});
		// search match string
		$("body").off("input",".onsearch").on("input",".onsearch", function(e) {
			e.preventDefault();
			dropdown.search($(this));
		});
		// event open selectbox menu
		$("body").off("click",".selectbox-content").on("click",".selectbox-content", function(e) {
			e.preventDefault();
			$(this).next().toggleClass("open");
			if($(this).next().hasClass("open")) {
				$(this).next().fadeIn();
			} else {
				$(this).next().fadeOut();
			}
		});
	},
	search: function(element) {
		var val = $.trim(element.val()).replace(/ +/g, " ").toLowerCase();
		$.each(element.parents(".drop-menu").find("li"), function() {
			$(this).show().filter(function() {
				var result = $(this).text().replace(/\s+/g, " ").toLowerCase();
				return !~ result.indexOf(val);
			}).hide();
		});
	}
};
/** End function dropdown select */

/**
	** function tooltip
	** @description
	** @example <p data-tooltip="true"></p>
*/
var tooltip = {
	init: function() {
		tooltip.render();
	},
	render: function() {
		$.each($("[data-tooltip=\"true\"]"), function() {
			if($("body").find(".show-tooltip").length < 1) {
				$("body").append("<span class=\"show-tooltip hidden\" />");
			}
		});
		tooltip.event();
	},
	event: function() {
		// set position tooltip
		var _events = function(el) {
			$.each($("[data-tooltip=\"true\"]"), function() {
				var $find = $("body").find(".show-tooltip"),
					_scroll = ($(window).scrollTop()+$(window).height()) - (el.offset().top);
				
				if(_scroll < is.trigger) {
					$find.css({
						left: el.offset().left + 'px',
						top: ((el.offset().top-el.outerHeight()) - 30) + 'px',
					});
					$find.removeClass("*arrow-").addClass("arrow-top");
				} else {
					$find.css({
						left: el.offset().left + 'px',
						top: ((el.offset().top+el.outerHeight()) + 15) + 'px',
					});
					$find.removeClass("*arrow-").addClass("arrow-bottom");
				}
			});
		}
		// remove position tooltip
		var _clear = function() {
			$.each($("[data-tooltip=\"true\"]"), function() {
				var $find = $("body").find(".show-tooltip");
				$find.removeClass("hidden").addClass("hidden");
				$find.removeClass("arrow-bottom").removeClass("arrow-top");
				$find.removeAttr("style");
			});
		}
		// event show hide tooltip
		$("body").off("mouseenter","[data-tooltip=\"true\"]").on("mouseenter","[data-tooltip=\"true\"]", function(e) {
			e.preventDefault();
			var $find = $("body").find(".show-tooltip");
			$find.removeClass("hidden");
			$find.text($(this).attr("data-title"));
			
			_events($(this));
			// when window resize
			$(window).resize(function() {
				waitForFinalEvent(function() {
					_events($(this));
				}, 100, "t00lt1p-p05");
			});
		});
		$("body").off("mouseleave","[data-tooltip=\"true\"]").on("mouseleave","[data-tooltip=\"true\"]", function(e) {
			e.preventDefault();
			_clear();
		});
	}
};
/** End function tooltip */

/**
	** function input text
	** @description
	** 1). Input with icon box
	** @example <input type="text" data-name="iconbox">
	** [data-icons], value: fontello iconname
	** [data-align], value: left/right
	** [data-color], value: color name
	** @noted gunakan fontello untuk memberi icon. List icon dapat dihat di web/core/lib/fontello/demo.html
	** 2). Spinner / input number
	** @example <input type="text" data-name="number" data-max="" data-min="">
*/
var input = {
	init: function() {
		input.render();
	},
	render: function() {
		$.each($("input[type=\"text\"]"), function() {
			switch($(this).attr("data-name")) {
				case "iconbox":
					if($(this).parents(".input-container").length < 1) {
						$(this).wrap("<div class=\"input-container\" />");
						$(this).after("<span class=\"box\"><i class=\"fontello "+$(this).attr("data-icons")+"\"></i></span>");
					}
					
					$(this).parents(".input-container").find(".box").css({
						backgroundColor: $(this).attr("data-color"),
						height: parseInt($(this).outerHeight()) +'px',
						width: parseInt($(this).outerHeight()) +'px',
					});
					
					if($(this).attr("data-align") == "left") {
						$(this).parents(".input-container").find(".box").addClass("data-left");
						$(this).parents(".input-container").find(".box").css({
							borderTopLeftRadius: parseInt($(this).css("border-top-left-radius")) +'px',
							borderBottomLeftRadius: parseInt($(this).css("border-bottom-left-radius")) +'px',
						});
					} else if($(this).attr("data-align") == "right") {
						$(this).parents(".input-container").find(".box").addClass("data-right");
						$(this).parents(".input-container").find(".box").css({
							borderTopRightRadius: parseInt($(this).css("border-top-right-radius")) +'px',
							borderBottomRightRadius: parseInt($(this).css("border-bottom-right-radius")) +'px',
						});
					}
				break;
				case "number":
					if($(this).parents(".input-container").length < 1) {
						$container = $([
							'<span class="arrow-btn">',
								'<span class="arrow arrow-up"><i class="fontello icon-up-micro"></i></span>',
								'<span class="arrow arrow-down"><i class="fontello icon-down-micro"></i></span>',
							'</span>'
						].join(''));
						
						$(this).wrap("<div class=\"input-container\" />");
						$(this).after($container);
						$(this).attr("number-only", true);
					}
					
					if(typeof $(this).attr("data-min") !== "undefined") {
						$(this).val($(this).attr("data-min"));
					}
					
					$(this).parents(".input-container").find(".arrow-btn").css({
						borderTopRightRadius: parseInt($(this).css("border-top-right-radius")) +'px',
						borderBottomRightRadius: parseInt($(this).css("border-bottom-right-radius")) +'px',
						height: parseInt($(this).outerHeight()) +'px',
						width: parseInt($(this).outerHeight()) / 2 +'px',
					});
					$(this).parents(".input-container").find(".arrow-btn > .arrow").css({
						height: parseInt($(this).outerHeight()) / 2 +'px',
						width: parseInt($(this).outerHeight()) / 2 +'px',
					});
				break;
			}
		});
		input.event();
	},
	event: function() {
		var _up = function(n) {
			var el = n.parents(".input-container").find("input[data-name=\"number\"]"),
				val = el.val(),
				max = el.attr("data-max");
			
			if(!val || val < 1 || val == "") {
				val = 0;
			}

			val = parseInt(val)+parseInt(1);
			// set max number
			if(typeof max !== "undefined") {
				if(val > max) val = max;
			}
			el.val(val);
		}
		
		var _down = function(n) {
			var el = n.parents(".input-container").find("input[data-name=\"number\"]"),
				val = el.val(),
				min = el.attr("data-min");
			
			if(!val || val < 0 || val == "") {
				val = 0;
			}
			if(parseInt(val) > 0) {
				val = parseInt(val)-1;
			}
			// set min number
			if(typeof min !== "undefined") {
				if(val < min) val = min;
			}
			el.val(val);
		}
		// event up number
		$("body").off("mousedown touchstart",".arrow-btn .arrow-up").on("mousedown touchstart",".arrow-btn .arrow-up", function(e) {
			e.preventDefault();
			var $this=$(this);
			is.timeOut = setInterval(function() {
				_down($this);
			}, 100);
		}).bind("mouseup mouseleave touchend", function() {
			clearInterval(is.timeOut);
		});
		// event down number
		$("body").off("mousedown touchstart",".arrow-btn .arrow-down").on("mousedown touchstart",".arrow-btn .arrow-down", function(e) {
			e.preventDefault();
			var $this=$(this);
			is.timeOut = setInterval(function() {
				_up($this);
			}, 100);
		}).bind("mouseup mouseleave touchend", function() {
			clearInterval(is.timeOut);
		});
	}
};
/** End function input text */

/**
	** function loader waiting
	** @description
	** loader ini melekat pada element / attribute / selector
	** this function called on $.fn.loader();
*/
var loader = {
	config: {},
	render: function(el) {
		var content = "<div class=\"loader-container\">";
		for(var i = 1; i <= 12; i++) {
			content += "<div class=\"loader-"+i+" loader\"></div>";
		}
		content += "</div>";
		if(el.siblings(".loader-container").length < 1) {
			el.after(content);
			$("head").append("<style id=\"loader-container\">");
		}
		
		var len = $(".loader-container [class^=loader-]").length,
			sec = [], animate = [], deg = [];
			
		for(var n = parseFloat(-1.1); n <= parseFloat(-.1); n = n+parseFloat(.1)) sec.push(n);
		for(var m = 1; m <= len-1; m++) {
			var _className = ".loader-container .loader-"+(m+1);
			var _animationDelay = [
				"animation-delay:" +sec[m-1].toFixed(1) +"s",
				"-o-animation-delay:" +sec[m-1].toFixed(1) +"s",
				"-ms-animation-delay:" +sec[m-1].toFixed(1) +"s",
				"-webkit-animation-delay:" +sec[m-1].toFixed(1) +"s",
				"-moz-animation-delay:" +sec[m-1].toFixed(1) +"s",
			];
			animate.push(_className +":before { "+ _animationDelay.toString().replace(/\,/g, ";") +"}");
		}
		
		for(var i = 0; i <= 330; i = i+30) deg.push(i);
		for(var j = 1; j <= len; j++) {
			$(".loader-"+j)
				.css('transform', 'rotate('+deg[j-1]+'deg)')
				.css('-ms-transform', 'rotate('+deg[j-1]+'deg)')
				.css('-o-transform', 'rotate('+deg[j-1]+'deg)')
				.css('-webkit-transform', 'rotate('+deg[j-1]+'deg)')
				.css('-moz-transform', 'rotate('+deg[j-1]+'deg)');
		}
		$("#loader-container").html(animate);
	},
	destroy: function() {
		$(".loader-container").remove();
		$("#loader-container").remove();
	}
};

var tableLoader = {
	config: {},
	render: function(el) {
		var content = "<div class=\"table-loader-container\">";
		content += "<div class=\"loader-container\">";
			for(var i = 1; i <= 12; i++) {
				content += "<div class=\"loader-"+i+" loader\"></div>";
			}
		content += "</div>";
		if(el.siblings(".table-loader-container").length < 1) {
			el.before(content);
			$("head").append("<style id=\"loader-container\">");
		}
		
		var len = $(".loader-container [class^=loader-]").length,
			sec = [], animate = [], deg = [];
			
		for(var n = parseFloat(-1.1); n <= parseFloat(-.1); n = n+parseFloat(.1)) sec.push(n);
		for(var m = 1; m <= len-1; m++) {
			var _className = ".loader-container .loader-"+(m+1);
			var _animationDelay = [
				"animation-delay:" +sec[m-1].toFixed(1) +"s",
				"-o-animation-delay:" +sec[m-1].toFixed(1) +"s",
				"-ms-animation-delay:" +sec[m-1].toFixed(1) +"s",
				"-webkit-animation-delay:" +sec[m-1].toFixed(1) +"s",
				"-moz-animation-delay:" +sec[m-1].toFixed(1) +"s",
				"background-color: #095698" ,
			];
			animate.push(_className +":before { "+ _animationDelay.toString().replace(/\,/g, ";") +"}");
		}
		
		for(var i = 0; i <= 330; i = i+30) deg.push(i);
		for(var j = 1; j <= len; j++) {
			$(".loader-"+j)
				.css('transform', 'rotate('+deg[j-1]+'deg)')
				.css('-ms-transform', 'rotate('+deg[j-1]+'deg)')
				.css('-o-transform', 'rotate('+deg[j-1]+'deg)')
				.css('-webkit-transform', 'rotate('+deg[j-1]+'deg)')
				.css('-moz-transform', 'rotate('+deg[j-1]+'deg)');
		}
		$("#loader-container").html(animate);
	},
	destroy: function() {
		$(".table-loader-container").remove();
	}
};
/** End loader function */

/**
	** function popup form / modal
	** @description
	** Untuk popup layout seperti modal bootstrap
	** this function called on $.fn.popup();
*/
var modal = {
	config: {
		draggable: false,
		fieldset: false,
		fieldsetOptions: {},
		enterKey: false,
		inputKey: false,
		initEvent: {
			checkbox: false,
			radiobox: false,
		},
		styleOptions: {},
	},
	open: function() {
		is.popup = true;
		// init container popup
		popup.layout();
		// init content
		modal.render();
	},
	render:  function() {
		$container = $([
			'<div class="popup-form" data-popup="form">',
				'<div class="popup-header">',
					'<h5></h5>',
					'<a href="javascript:void(0)" class="popup-remove" id="btn-remove">',
						'<i class="fontello dark-blue icon-cancel-2"></i>',
					'</a>',
				'</div>',
				'<div class="popup-body"></div>',
			'</div>'
		].join(''));
		$container.appendTo(".popup");
	}
};
/** End popup form / modal function */

/**
	** function draggable element
	** define object options here
	** this function called on $.fn.drag();
*/
var draggable = {
	config: {
		handle: "",
		draggableClass: "drag",
	},
};
/** End draggable function */

/** 
	** function file upload
	** Fungsi ini untuk tampilan custom fileupload
*/
var uploadFile = {
	init: function(el) {
		uploadFile.event(el);
		if(is.upload == true) {
			$("#save_csvfile").removeAttr("disabled");
		}
	},
	event: function(el) {
		$("body").off("change","input[type=file]").on("change","input[type=file]", function(e) {
			e.preventDefault();
			var imgpath = $(this)[0].value, 
				extn = imgpath.substring(imgpath.lastIndexOf(".") + 1).toLowerCase();
			
			if(extn == "csv" || extn == "xls" || extn == "xlsx") { // type document
				uploadFile.reader("document", el);
				// el.text($(this).val());
				el.val($(this).val());
			} else if(extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") { // type image
				uploadFile.reader("image", el);
				el.val($(this).val());
			} else
				notification.open("danger", "Format is not validate.", 5000);
		});
	},
	reader: function(ext, el) {
		if(typeof(FileReader) !== "undefined") {
			el.empty(); // default empty
			var reader = new FileReader();
			if(ext == "document") {
				reader.onload = function(e) {
					$("<span />", { "title": e.target.result }).appendTo(el);
				}
			} else if(ext == "image") {
				reader.onload = function(e) {
					$("<img />", { "src": e.target.result, "class": "custom-fileupload" }).appendTo(el);
				}
			}
			is.upload = true;
			uploadFile.init(el);
			el.show();
			// reader.readAsDataURL($(this)[0].files[0]);
		} else {
			is.upload = false;
			console.log("Don't support File Reader");
		}
	}
};
/** end function file upload */

/** 
	** function file download
	** Fungsi ini untuk download file dari sistem
*/
var download = {
	open: function(url) {
		window.open(url, '_blank');
	},
	save: function(url, name) {
		if(!window.ActiveXObject){
			var el = document.createElement("a");
			el.href = url;
			el.target = '_blank';
			
			var names = url.substring(url.lastIndexOf('/') +1);
			el.download = name || names;
			
			if((navigator.userAgent.toLowerCase().match(/(ipad|iphone|safari)/)) && (navigator.userAgent.search("Chrome") < 0)){
				document.location = el.href;
			} else{
				var evt = new MouseEvent("click", {
					'view': window,
					'bubbles': true,
					'cancelable': false, 
				});
				el.dispatchEvent(evt);
				(window.URL || window.webkitURL).revokeObjectURL(el.href);
			}
		} else if(!!window.ActiveXObject && document.execCommand){
			var w = window.open(url, '_blank');
			w.document.close();
			w.document.execCommand('SaveAs', true, name || url);
			w.close();
		}
	}
};