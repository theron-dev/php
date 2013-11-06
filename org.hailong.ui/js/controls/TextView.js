
UI.TextView = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "text"){
			el.val(value);
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	
	bindElement:function(el){
		UI.View.bindElement(el);
		
		el.bind("change",function(){
			var $this = $(this);
			var id =$this.attr("id");
			if(id){
				viewSetAttribute(id,"text",$this.val());
				var action = viewGetAttribute(id,"change-action");
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
