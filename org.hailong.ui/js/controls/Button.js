
UI.Button = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "title"){
			el.val(value);
		}
		else if(name == "disabled"){
			if(value){
				el.attr("disabled","disabled");
			}
			else{
				el.removeAttr("disabled");
			}
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	
	bindElement:function(el){
		UI.View.bindElement(el);
		
		el.bind("click",function(){
			var $this = $(this);
			var id =$this.attr("id");
			if(id){
				var action = viewGetAttribute(id,"click-action");
				if(action){
					el.attr("disabled","disabled");
					viewAction(action,function(){
						el.removeAttr("disabled");
					});
				}
			}
		});
	}
});
