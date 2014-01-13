
(function(publish){
	
	
	var plugin = null;
	
	for(plugin in publish.Plugins){
		if(plugin.match && typeof plugin.match == "function"){
			if(plugin.match()){
				break;
			}
		}
		else if(plugin.hasPrefix && typeof plugin.hasPrefix == "string"){
			var hasPrefix = plugin.hasPrefix;
			var len = hasPrefix.length;
			var href = window.location.href;
			if(len <= href.length){
				var i = 0;
				for( i=0;i<len;i++){
					if(hasPrefix[i] != href[i]){
						break;
					}
				}
				if( i >= len){
					break;
				}
			}
		}
	}
	
	var html = "<form width='100%'";
	
})(VTPublish);
