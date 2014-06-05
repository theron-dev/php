
UI.Template = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "items"){
			
			var p = el.parent();
			var childs = p.children();
			for(var i=0;i< childs.length;i++){
				if(childs[i] != el[0]){
					$(childs[i]).remove();
				}
			}
			
			if(value && value.length !== undefined){
				
				var fn = function(element,data){
					var dataBind = element.attr("databind");
					if(dataBind){
						
						var binds = dataBind.split(";");
						var v = function(key,index,value){
							
							var keys;
							
							if(typeof key == "string"){
								keys = key.split(".");
							}
							else {
								keys = key;
							}
							
							if(index === undefined){
								index = 0;
							}
							
							if(value === undefined){
								value = data;
							}
							
							var vv = value[keys[index]];
							
							if(vv!== undefined && vv !== null){
								if(index + 1 < keys.length){
									return v(keys,index +1,vv);
								}
								return vv;
							}
							
							return vv;
						};
						
						for(var i=0;i<binds.length;i++){
							var bind = binds[i];
							var kv = bind.split(":");
							if(kv.length >=2){
								if(kv[0] == "text"){
									element.html(eval(kv[1]));
								}
								else {
									element.attr(kv[0],eval(kv[1]));
								}
							}
						}
					}
					var childs = element.children();
					for(var i=0;i<childs.length;i++){
						fn($(childs[i]),data);
					}
				};
				
				for(var i=0;i<value.length;i++){
					var item = value[i];
					var element = el.clone();
					
					element.attr("id","");
					element.attr("view","");
					
					fn(element,item);
					
					element.show();
					
					p.append(element);
				}
				
			}
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	
	bindElement:function(el){
		UI.View.bindElement(el);
		el.hide();
	}
});
