
UI.DateText = $.extend({},UI.TextView,{
	
	updateAttribute:function(el,name,value){
		if(name == "dateFormat"){
			el.datepicker( "option", "dateFormat", value );
		}
		else{
			UI.TextView.updateAttribute(el,name,value);
		}
	},
	
	bindElement:function(el){
		UI.TextView.bindElement(el);
		var dateFormat = el.attr("dateFormat");
		if(!dateFormat){
			dateFormat = "yy-mm-dd";
		}
		el.datepicker().datepicker("option", "dateFormat", dateFormat);
	}
});
