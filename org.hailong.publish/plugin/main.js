
VTPublish = {
		
		jQuery:"/js/jquery-2.0.2.min.js",
		Plugins:[],
		
		run:function(script){
			
			var baseUrl = script.src;
			var i = baseUrl.lastIndexOf("/");
		
			this.baseUrl = baseUrl.substr(0,i);
			
			
			
		},
		
		loadScript:function(src){
			var i = src.indexOf("http://");
			if(i !== 0){
				src = this.baseUrl + src;
			}
			var element = document.createElement("script");
			element.type = "text/javascript; charset=utf8";
			element.charset = "utf8";
			element.src = src;
			document.body.append(Child);
		},
		
		plugin: function(){
			
			var plugin = null;
			
			for(plugin in this.Plugins){
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
			
			return plugin;
		},
		
		show: function(){
			
			
			
		}
};

if(!window.jQuery){
	VTPublish.loadScript(VTPublish.jQuery);
}

VTPublish.loadScript("/plugins/read.douban.com_ebook.js");
