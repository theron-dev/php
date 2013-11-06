
UI.ActionView = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "html"){
			el.html(value);
		}
		else if(name == "hidden"){
			el.css("display",value ? "none":"block");
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	}
	,bindElement:function(element){
		UI.View.bindElement(element);
	
		var fn = function(e){
			var id = element.attr("id");
			var el =$(e.target);
			if(id ){
				if(el.is("input[action],a[action]")){
					var action = viewGetAttribute(id,"click-action");
					if(action){
						viewSetAttribute(id,"action",el.attr("action"));
						viewSetAttribute(id,"actionKey",el.attr("key"));
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
