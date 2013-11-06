
UI.WebView = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "url"){
			el.attr("src",value);
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	
	bindElement:function(el){
		UI.View.bindElement(el);
	}
});
