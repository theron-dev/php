
UI.Label = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "text"){
			el.html(value);
		}
		else if(name == "hidden"){
			el.css("display",value ? "none":"block");
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	}
	
});
