
User = {};

User.Toolbar = $.extend({},UI.View,{
		
		updateAttribute:function(el,name,value){
			if(name == "items"){
				var html = [];
				if(value && value.length !== undefined){
					for(var i=0;i<value.length;i++){
						var item = value[i];
						html.push("<li v='");
						html.push(item["value"]);
						html.push("' >");
						html.push(item["text"]);
						html.push("</li>");
					}
				}
				el.html(html.join(""));
				$("li",el).hover(
						function(e){
							$(this).addClass("hover");
						}
						,function(e){
							$(this).removeClass("hover");
						});
			}
			else{
				UI.View.updateAttribute(el,name,value);
			}
		},
		onUpdated:function(el){
			$("li",el).removeClass("selected");
			var selectedValue = viewGetAttribute(el.attr("id"),"selectedValue");
			if(selectedValue){
				$("li[v='"+selectedValue+"']",el).addClass("selected");
			}
		},
		bindElement:function(element){
			UI.View.bindElement(element);
			
			var fn = function(e){
				var $this = $(this);
				var id = $this.attr("id");
				var el = $(e.target);
				if(el.is("li")){
					$("li",$this).removeClass("selected");
					el.addClass("selected");
					if(id){
						viewSetAttribute(id,"selectedValue",el.attr("v"));
						var action = viewGetAttribute(id,"selected-action");
						if(action){
							element.unbind("click",fn);
							viewAction(action,function(){
								element.bind("click",fn);
							});
						}
					}
				}
			};
			element.bind("click",fn);
			$("li",element).hover(
					function(e){
						$(this).addClass("hover");
					}
					,function(e){
						$(this).removeClass("hover");
					});
			
			var el = $("li.selected");
			var id = el.attr("id");
			var v = el.attr("v");
			if(id && v != viewGetAttribute(id,"selectedValue")){
				viewSetAttribute(id,"selectedValue",el.attr("v"));
			}
		}
	});

User.EntityList = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "items"){
			var html = [];
			if(value && value.length !== undefined){
				for(var i=0;i<value.length;i++){
					var item = value[i];
					html.push("<li v='");
					html.push(item["value"]);
					html.push("' title='")
					html.push(item["title"]);
					html.push("' >");
					html.push(item["text"]);
					html.push("</li>");
				}
			}
			var list = $(".list",el);
			list.html(html.join(""));
			$("li",list).hover(
					function(e){
						$(this).addClass("hover");
					}
					,function(e){
						$(this).removeClass("hover");
					});
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	onUpdated:function(el){
		var list = $(".list",el);
		$("li",list).removeClass("selected");
		var selectedValue = viewGetAttribute(el.attr("id"),"selectedValue");
		if(selectedValue){
			$("li[v='"+selectedValue+"']",list).addClass("selected");
		}
	},
	bindElement:function(element){
		UI.View.bindElement(element);
		
		var fn = function(e){
			var $this = $(this);
			var id = $this.attr("id");
			var el = $(e.target);
			if(el.is("li")){
				$("li",$this).removeClass("selected");
				el.addClass("selected");
				if(id){
					viewSetAttribute(id,"selectedValue",el.attr("v"));
					var action = viewGetAttribute(id,"selected-action");
					if(action){
						element.unbind("click",fn);
						viewAction(action,function(){
							element.bind("click",fn);
						});
					}
				}
			}
		};
		element.bind("click",fn);
		$("li",element).hover(
				function(e){
					$(this).addClass("hover");
				}
				,function(e){
					$(this).removeClass("hover");
				});
		
		var search = $(".search",element);
		var list = $(".list",element);
		var keywrod = search.val();
		var testKeywordTimeout = null;
		
		var testKeyword = function(){
			if(keyword != search.val()){
				keyword = search.val();
				var items = $("li",list);
				var keyword = search.val();
				if(keyword){
					var regexp = new RegExp(keyword+"*");
					for(var i=0;i<items.size();i++){
						var item = $(items[i]);
						if(regexp.test(item.html())){
							item.show();
						}
						else{
							item.hide();
						}
					}
				}
				else{
					items.show();
				}
			}
			testKeywordTimeout = window.setTimeout(testKeyword,60);
		};
		search.bind("focus",function(){
			if(testKeywordTimeout ==null){
				testKeywordTimeout = window.setTimeout(testKeyword,0);
			}
		})
		.bind("blur",function(){
			if(testKeywordTimeout){
				window.clearTimeout(testKeywordTimeout);
				testKeywordTimeout = null;
			}
		});
	}
});

User.Dialog = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "dialog"){
			$(".dialog",el).hide();
			if(value){
				$(".dialog["+value+"]").show();
				el.show();
			}
			else{
				el.hide();
			}
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	bindElement:function(element){
		UI.View.bindElement(element);
		element.css("alpha",0.3);
	}
});

