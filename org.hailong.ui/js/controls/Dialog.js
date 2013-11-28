
UI.Dialog = $.extend({},UI.View,{
	
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

