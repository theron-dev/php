
ViewStateVersion = 0;
ViewState = {};
ViewStateUpdated = {};


function engine(data){
	ViewStateVersion = data["version"];
	ViewState = data["data"];
	
	var bindFn = function(element){
		var el = $(element);
		var view = el.attr("view");
		if(view){
			view = eval(view);
			if(view){
				view.bindElement(el);
			}
		}
		var childNodes = element.childNodes;
		for(var i=0;i<childNodes.length;i++){
			var node = childNodes[i];
			if(node.nodeType == 1){
				bindFn(node);
			}
		}
	}
	
	bindFn(document.body);
	
	viewUpdateAttributes(ViewState);
	viewPushAttributes(data["push-data"]);
	viewPushFunctions(data["push-functions"]);
}

function viewUpdateAttributes(data){
   if(data){
	   for(var viewId in data){
		   var state = {};
		   if(ViewState != data){
			   state = ViewState[viewId];
			   if(state === undefined){
				   state = {};
				   ViewState[viewId] = state;
			   }
		   }
		   var attrs = data[viewId];
		   for(var key in attrs){
			   state[key] = attrs[key];
			   var el = $("#"+viewId);
			   if(el.size() >0){
				   var view = el.attr("view");
				   if(view){
					   view = eval(view);
					   if(view){
						   view.updateAttribute(el,key,attrs[key]);
					   }
				   }
			   }
		   }
	   }
	   
	   for(var viewId in data){
		   var el = $("#"+viewId);
		   if(el.size() >0){
			   var view = el.attr("view");
			   if(view){
				   view = eval(view);
				   if(view){
					   view.onUpdated(el);
				   }
			   }
		   }
	   }
   }
}

function viewPushAttributes(data){
   if(data){
	   for(var viewId in data){
		   var attrs = data[viewId];
		   for(var key in attrs){
			   var el = $("#"+viewId);
			   if(el.size() >0){
				   var view = el.attr("view");
				   if(view){
					   view = eval(view);
					   if(view){
						   view.updateAttribute(el,key,attrs[key]);
					   }
				   }
			   }
			   else{
				   try{
					   var e = eval(viewId);
					   if(e){
						   e[key] = attrs[key];
					   }
				   }
				   catch(ex){};
			   }
		   }
	   }
	   for(var viewId in data){
		   var el = $("#"+viewId);
		   if(el.size() >0){
			   var view = el.attr("view");
			   if(view){
				   view = eval(view);
				   if(view){
					   view.onUpdated(el);
				   }
			   }
		   }
	   }
   }
}

function viewPushFunctions(data){
	if(data){
		for(var fn in data){
			var args = data[fn];
			try{
				var func = eval(fn);
				if(typeof func == 'function'){
					if(typeof args == 'object'){
						func.apply(null,args);
					}
					else{
						func.call(null,args);
					}
				}
		    }
		    catch(ex){};
			
		}
	}
}

function viewSetAttribute(viewId,name,value){
	var v = ViewState[viewId];
	if(v === undefined){
		v = {};
		ViewState[viewId] = v;
	}
	v[name] = value;
	
	v = ViewStateUpdated[viewId];
	
	if(v === undefined){
		v = {};
		ViewStateUpdated[viewId] = v;
	}
	v[name] = value;
}

function viewGetAttribute(viewId,name){
	var v = ViewState[viewId];
	if(v){
		return v[name];
	}
	return null;
}

var loadingTimeout = null;

function formMultipartAction(form,action){
	
	var el = document.createElement("input");
	
	el.type = "hidden";
	el.name = "data-json";
	el.value = encodeJson({version:ViewStateVersion,data: ViewStateUpdated});
	
	form.appendChild(el);
	
	for(var key in action){
		
		var value = action[key];
		
		el = document.createElement("input");
		el.type = "hidden";
		el.name = key;
		if(value instanceof Object && value["vid"]){
			el.value = viewGetAttribute(value["vid"],value["name"]);
		}
		else{
			el.value = value;
		}
		
		form.appendChild(el);
	}
	
	return true;
}

function viewAction(action,callback,loading){
	
	var data = {};
	data["data-json"] = encodeJson({version:ViewStateVersion,data: ViewStateUpdated});
	
	for(var key in action){
		var value = action[key];
		if(value instanceof Object && value["vid"]){
			data[key] = viewGetAttribute(value["vid"],value["name"]);
		}
		else{
			data[key] = value;
		}
	}
	
	var loadingView = document.getElementById("loading");
	
	if(!loadingView){
		try{
			if(window.parent && window.parent.document){
				loadingView = window.parent.document.getElementById("loading");
			}
		}
		catch(e){};
	}
	
	loadingView = $(loadingView);
	
	if(loading){
		loadingView.html(loading);
	}
	else{
		loadingView.html("加载中..");
	}
	
	loadingTimeout = window.setTimeout(function(){
		loadingView.show();
	},300);
	
	var fn = function(result) {
		
		if(loadingTimeout){
		   window.clearTimeout(loadingTimeout);
		   loadingTimeout = null;
	   }
	   loadingView.hide();
	   if(result){
		   if(result["error-code"]){
		   alert(result["error"]);
		   window.location.reload();
	   }
	   else{
		   ViewStateVersion = result["version"];
		   viewUpdateAttributes(result["data"]);
		   viewPushAttributes(result["push-data"]);
		   viewPushFunctions(result["push-functions"]);
		   ViewStateUpdated = {};
		   if(callback && typeof callback == 'function'){
				   callback.call(action);
			   }
		   }
	   }
	   else{
		   alert("server error");
		   window.location.reload();
	   }
	};
	
	$.ajax({
		  url:window.location.href,
		  type:"POST",
		  data:data,
		  contentType:"application/x-www-form-urlencoded; charset=utf-8",
		  dataType:"json",
		  success: fn
		})
}

function viewValidateClear(element){
	$("input[regexp],textarea[regexp],select[regexp]",element).removeClass("error");
}

function viewValidate(element){
	var validate = true;
	var message = null;
	var items = $("input[regexp],textarea[regexp],select[regexp]",element);
	for(var i=0;i<items.size();i++){
		var item = $(items[i]);
		var exp = item.attr("regexp");
		var vali = false;
		if(exp && exp != "-"){
			vali = new RegExp(exp).test(item.val());
		}
		else{
			vali = !!item.val();
		}
		if(!vali){
			item.addClass("error");
			if(!message){
				message = item.attr("message");
			}
			validate = false;
		}
	}
	if(!validate && message){
		alert(message);
	}
	return validate;
}

function encodeJson(data){
	if(data == null){
		return "null";
	}
	if(data instanceof Object){
		if(data instanceof Array){
			var rs = [];
			for(var i=0;i<data.length;i++){
				rs.push(encodeJson(data[i]));
			}
			return "["+rs.join(",")+"]";
		}
		else{
			var rs = [];
			for(var key in data){
				rs.push('"'+key+'":'+encodeJson(data[key]));
			}
			return "{"+rs.join(",")+"}";
		}
	}
	if(typeof data == "string"){
		var v = data.replace(/\\/g,"\\\\").replace(/\"/g,"\\\"");
		return "\""+v + "\"";
	}
	else{
		return "" + data;
	}
}

$(function(){
	
	if(window.PostData){
		engine(PostData);
		delete PostData;
	}
});

