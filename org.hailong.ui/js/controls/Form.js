
UI.Form = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "fields"){
			if(value){
				for(var key in value){
					var v = value[key];
					$("input[name='"+key+"'],select[name='"+key+"'],textarea[name='"+key+"']",el).val(v);
				}
			}
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	
	bindElement:function(el){
		UI.View.bindElement(el);
		
		el.bind("submit",function(){
			var $this = $(this);
			var id =$this.attr("id");
			viewValidateClear($this);
			if(viewValidate($this) && id){
				
				var fields = $("input[name],select[name],textarea[name]",$this);
				var fs = {};
				
				for(var i=0;i<fields.size();i++){
					var field = fields.eq(i);
					var name = field.attr("name");
					if(field[0].tagName == "input"){
						var type = field.attr("type");
						if(type == "radio"){
							if(field[0].checked){
								fs[name] = field.val();
							}
						}
						else if(type == "checkbox"){
							if(field[0].checked){
								var v = fs[name];
								if(v == undefined){
									v = [];
								}
								v.push(field.val());
								fs[name] = v;
							}
						}
						else if(type == "text" || type=="password" || type=="hidden"){
							fs[name] = field.val();
						}
					}
					else{
						fs[name] = field.val();
					}
				}
				
				viewSetAttribute(id,"fields",fs);
				
				var action = viewGetAttribute(id,"submit-action");
				if(action){
					
					var enctype = $this.attr("enctype");
					
					if(enctype == "multipart/form-data"){
						
						return formMultipartAction(this,action);
						
					}
					else{
						$("input[type='submit']",el).attr("disabled","disabled");
						viewAction(action,function(){
							$("input[type='submit']",el).removeAttr("disabled");
						});
					}
				}
			}
			return false;
		});
	}
	
});
