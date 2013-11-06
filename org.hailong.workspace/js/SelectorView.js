

Studio.SelectorView = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "items"){
			var group = null;
			var html = [];
			if(value && value.length !== undefined){
				for(var i=0;i<value.length;i++){
					var item = value[i];
					var g = item["group"];
					if(g && g != group){
						html.push("<li class='group'><span>"+g+"</span></li>");
						group = g;
					}
					html.push("<li class='item' href='");
					html.push(item["href"]);
					html.push("' v='");
					html.push(item["value"]);
					html.push("'><table><tbody><tr><td>");
					var icon = item["icon"];
					if(icon){
						html.push("<img src='"+icon+"' />");
					}
					else{
						html.push("<img  />");
					}
					html.push("<span>"+item["title"]+"</span>");
					html.push("</td></tr></tbody></table></li>");
				}
			}
			el.html(html.join(""));
			$("li.item",el).hover(
					function(e){
						
						$(this).addClass("hover");
					}
					,function(e){
						$(this).removeClass("hover");
					});
		}
		else if(name == "selectedValue"){

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
			var el = $(e.target).parents("li");
			if(el.is("li.item")){
				$("li.item",$this).removeClass("selected");
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
		
	}
});
