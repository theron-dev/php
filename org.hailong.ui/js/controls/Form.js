
UI.Form = $.extend({},UI.View,{
	
	updateAttribute:function(el,name,value){
		if(name == "fields"){
			if(value){
				
				var fields = $("input[name],select[name],textarea[name]",el);
				
				for(var i=0;i<fields.size();i++){
					var field = fields.eq(i);
					var name = field.attr("name");
					var tagName = field[0].tagName;
					var v = value[name];
					if(v !== undefined){	
						if(tagName == "INPUT"){
							var type = field.attr("type");
							if(type == "radio"){
								field[0].checked = (v == field.val());
							}
							else if(type == "checkbox"){
								
								if(v && v.length !== undefined){
									var ii = v.indexOf(field.val());
									field[0].checked = ii >= 0;
								}
								else if(v){
									field[0].checked = v == field.val();
								}
								else{
									field[0].checked = false;
								}
								
							}
							else if(type == "hidden"){
								field.val(v);
								
								var fn = field[0].onsetted;
								
								if(!fn){
									fn = field.attr("onsetted");
								}
								
								if(fn){
									if(typeof fn == 'function'){
										fn.call(field[0]);
									}
									else{
										var ffn = function(){
											eval(fn);
										};
										ffn.call(field[0]);
									}
								}
								
							}
							else if(type == "text" || type=="password" ){
								field.val(v);
							}
						}
						else{
							field.val(v);
						}
					}
	
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
					var tagName = field[0].tagName;
					if(tagName == "INPUT"){
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
						else if(type == "hidden"){
							var fn = field[0].onsubmit;
							if(!fn){
								fn = field.attr("onsubmit");
							}
							if(fn){
								if(typeof fn == "function"){
									fn.call(field[0]);
								}
								else{
									var ffn = function(){
										eval(fn);
									};
									ffn.call(field[0]);
								}
							}
							fs[name] = field.val();
						}
						else if(type == "text" || type=="password" || type=="hidden" || type=="file"){
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
