
UI = {};

UI.View = {
	updateAttribute:function(el,name,value){
		if(name == "styleName"){
			if(el.size()>0){
				el[0].className = value;
			}
		}
		else if(name == "hidden"){
			el.css("display",value ? "none":"block");
		}
		else if(typeof value =='object'){
			
		}
		else{
			el.attr(name,value);
		}
	},
	
	bindElement:function(el){
		
	},
	
	onUpdated:function(el){
		
	}
	
};