
UI.Select = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "items"){
			el.html("");
			var html = [];
			if(value && typeof value =='object' && value.length !== undefined){
				for(var i=0;i<value.length;i++){
					var item = value[i];
					html.push("<option value='");
					html.push(item["value"]);
					html.push("' ");
					if(item["selected"]){
						html.push(" selected='selected'");
					}
					html.push(">");
					html.push(item["text"]);
					html.push("</option>");
				}
			}
			el.html(html.join(""));
		}
		else if(name == "selectedValue"){
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	
	onUpdated:function(el){
		var selectedValue = viewGetAttribute(el.attr("id"),"selectedValue");
		if(selectedValue){
			el.val(selectedValue);
		}
	},
	
	bindElement:function(el){
		UI.View.bindElement(el);
		
		var fn = function(){
			var $this = $(this);
			var id =$this.attr("id");
			if(id){
				viewSetAttribute(id,"selectedValue",$this.val());
				var action = viewGetAttribute(id,"selected-action");
				if(action){
					el.attr("disabled","disabled");
					viewAction(action,function(){
						el.removeAttr("disabled");
					});
				}
			}
		};
		el.bind("change",fn);
	}
});
