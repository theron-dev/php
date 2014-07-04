
UI.TableView = $.extend({},UI.View,{
	

	updateAttribute:function(el,name,value){
		if(name == "items"){
			var thead = $(">thead",el);
			var tbody = $(">tbody",el);
			var ths = $("th",thead);
			
			tbody.html("");
			var html = [];
			
			if(value && value.length !== undefined){
				for(var n=0;n<value.length;n++){
					var item = value[n];
					html.push("<tr>");
					for(var i=0;i<ths.size();i++){
						html.push("<td>");
						var th = $(ths[i]);
						var field = th.attr("field");
						if(field){
							var v = item[field];
							if(v !== undefined && v !== null){
								html.push(v);
							}
						}
						html.push("</td>");
					}
					html.push("</tr>");
					var childs = item["childs"];
					if(childs){
						var head = childs["head"];
						var items = childs["items"];
						if(items === undefined){
							items = childs;
						}
						html.push("<tr class='child'><td colspan='"+ths.size()+"'><table>");
						if(head && head.length !== undefined){
							html.push("<thead><tr>");
							for(var i=0;i<head.length;i++){
								var th = head[i];
								html.push("<th>");
								html.push(th["title"]);
								html.push("</th>");
							}
							html.push("</tr></thead>");
						}
						html.push("<tbody>");
						if(items && items.length !== undefined){
							for(var i=0;i<items.length;i++){
								var item = items[i];
								html.push("<tr>");
								if(head && head.length !== undefined){
									for(var j=0;j<head.length;j++){
										var th = head[j];
										html.push("<td>");
										html.push(item[th["key"]]);
										html.push("</td>");
									}
								}
								else{
									for(var key in item){
										html.push("<td>");
										html.push(item[key]);
										html.push("</td>");
									}
								}
								html.push("</tr>");
							}
						}
						html.push("</tbody>");
						html.push("</table></td></tr>");
					}
				}
			}
			tbody.html(html.join(""));
			
			$("tbody>tr",el).hover(function(e){
				$(this).addClass("hover");
			}
			,function(e){
				$(this).removeClass("hover");
			});
		}
		else{
			UI.View.updateAttribute(el,name,value);
		}
	},
	
	bindElement:function(element){
		UI.View.bindElement(element);
		
		var fn = function(e){
			var id = element.attr("id");
			var el =$(e.target);
			if(id ){
				if(el.is("input[action],a[action]")){
					var action = viewGetAttribute(id,"click-action");
					if(action){
						viewSetAttribute(id,"action",el.attr("action"));
						viewSetAttribute(id,"actionKey",el.attr("key"));
						viewSetAttribute(id,"actionData",null);
						element.unbind("click",fn);
						viewAction(action,function(){
							element.bind("click",fn);
						});
					}
				}
				else if(el.is("input.edit")){
					var ths = $("th",$("thead",element));
					var tr = el.parents("tr");
					var tds = $("td",tr);

					for(var i=0;i<ths.size();i++){
						var cell = $(tds[i]);
						var th = $(ths[i]);
						if(th.attr("field") == "command"){
							cell.children().hide();
							var input = document.createElement("input");
							input.type = "button";
							input.value = "确定";
							input.className = "edit-ok";
							input.setAttribute("key",el.attr("key"));
							cell.append(input);
							
							input = document.createElement("input");
							input.type = "button";
							input.value = "取消";
							input.className = "edit-cancel";
							cell.append(input);
						}
						else if(th.attr("editable")){
							var input ;
							if(th.attr("multi-line")){
								input = document.createElement("textarea");
							}
							else{
								input = document.createElement("input");
								input.type = "text";
							}
							input.className = "edit-input";
							var field  = th.attr("field");
							if(field !== undefined){
								input.setAttribute("field" , field);
							}
							var regexp = th.attr("regexp");
							if(regexp !== undefined){
								if(!regexp){
									regexp = "-";
								}
								input.setAttribute("regexp", regexp);
							}
							var value = th.attr("defaultValue");
							if(value !== undefined && value !== null){
								input.value = value;
							}
							input.value = cell.text();
							input.setAttribute("cv",cell.html());
							cell.html("");
							cell.append(input);
						}
					}
				}
				else if(el.is("input.edit-cancel")){
					var ths = $("th",$("thead",element));
					var tr = el.parents("tr");
					var tds = $("td",tr);

					for(var i=0;i<ths.size();i++){
						var cell = $(tds[i]);
						var th = $(ths[i]);
						if(th.attr("field") == "command"){
							$(".edit-ok,.edit-cancel",cell).remove();
							cell.children().show();
						}
						else if(th.attr("editable")){
							var input = $(".edit-input",cell);
							input.remove();
							cell.html(input.attr("cv"));
						}
					}
				}
				else if(el.is("input.edit-ok")){
					var tr = el.parents("tr");
					viewValidateClear(tr);
					if(viewValidate(tr)){
						var action = viewGetAttribute(id,"click-action");
						if(action){
							viewSetAttribute(id,"action","edit");
							viewSetAttribute(id,"actionKey",el.attr("key"));
							var data = {};
							var inputs = $("input[field],textarea[field]",tr);
							for(var i=0;i<inputs.size();i++){
								var input = $(inputs[i]);
								data[input.attr("field")] = input.val();
							}
							viewSetAttribute(id,"actionData",data);
							element.unbind("click",fn);
							viewAction(action,function(){
								element.bind("click",fn);
							});
						}
						
						var ths = $("th",$("thead",element));
						var tds = $("td",tr);

						for(var i=0;i<ths.size();i++){
							var cell = $(tds[i]);
							var th = $(ths[i]);
							if(th.attr("field") == "command"){
								$(".edit-ok,.edit-cancel",cell).remove();
								cell.children().show();
							}
							else if(th.attr("editable")){
								var input = $(".edit-input",cell);
								input.remove();
								cell.html(input.attr("cv"));
							}
						} 
					}
				}
				else if(el.is("input.add")){
					var ths = $("th",$("thead",element));
					var tbody = $("tbody",element);
					if(tbody.size() >0){
						tbody = tbody[0];
						var row =tbody.insertRow(tbody.rows.length);
						for(var i=0;i<ths.size();i++){
							var cell = i < row.cells.length ? row.cells[i] : row.insertCell(i);
							var th = $(ths[i]);
							if(th.attr("field") == "command"){
								var input = document.createElement("input");
								input.type = "button";
								input.value = "确定";
								input.className = "add-ok";
								cell.appendChild(input);
								
								input = document.createElement("input");
								input.type = "button";
								input.value = "取消";
								input.className = "add-cancel";
								cell.appendChild(input);
							}
							else if(th.attr("editable")){
								var input ;
								if(th.attr("multi-line")){
									input = document.createElement("textarea");
								}
								else{
									input = document.createElement("input");
									input.type = "text";
								}
								input.type = "text";
								var field  = th.attr("field");
								if(field !== undefined){
									input.setAttribute("field" , field);
								}
								var regexp = th.attr("regexp");
								if(regexp !== undefined){
									if(!regexp){
										regexp = "-";
									}
									input.setAttribute("regexp", regexp);
								}
								var value = th.attr("defaultValue");
								if(value !== undefined && value !== null){
									input.value = value;
								}
								cell.appendChild(input);
							}
							row.appendChild(cell);
						}
						tbody.appendChild(row);
						el.removeAttr("disabled");
					}
				}
				else if(el.is("input.add-cancel")){
					var tr = el.parents("tr").remove();
					$("input.add",element).removeAttr("disabled");
				}
				else if(el.is("input.add-ok")){
					var tr = el.parents("tr");
					viewValidateClear(tr);
					if(viewValidate(tr)){
						var action = viewGetAttribute(id,"click-action");
						if(action){
							viewSetAttribute(id,"action","add");
							viewSetAttribute(id,"actionKey","");
							var data = {};
							var inputs = $("input[field],textarea[field]",tr);
							for(var i=0;i<inputs.size();i++){
								var input = $(inputs[i]);
								data[input.attr("field")] = input.val();
							}
							viewSetAttribute(id,"actionData",data);
							element.unbind("click",fn);
							viewAction(action,function(){
								element.bind("click",fn);
							});
						}
						tr.remove();
						$("input.add",element).removeAttr("disabled");
					}
				}
			}
		};
		
		element.bind("click",fn);
		
		$("tbody>tr",element).hover(function(e){
			$(this).addClass("hover");
		}
		,function(e){
			$(this).removeClass("hover");
		});
	}
	
});
