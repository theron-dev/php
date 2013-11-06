
UI.LinkButton = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "title"){
			el.html(value);
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	
	bindElement:function(el){
		UI.View.bindElement(el);
		
		var fn = function(){
			var $this = $(this);
			var id =$this.attr("id");
			if(id){
				var action = viewGetAttribute(id,"click-action");
				if(action){
					el.unbind("click",fn);
					viewAction(action,function(){
						el.bind("click",fn);
					});
				}
			}
		};
		el.bind("click",fn);
	}
});
