
UI.Link = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "text"){
			el.html(value);
		}
		else if(name == "href"){
			el.attr("href",value);
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	
	bindElement:function(el){
		UI.View.bindElement(el);
	}
});
