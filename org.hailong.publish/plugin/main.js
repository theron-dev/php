
VTPublish = {
		
		jQuery:"/js/jquery-2.0.2.min.js",
		Plugins:[],
		
		run:function(script){
			
			var baseUrl = script.src;
			var i = baseUrl.lastIndexOf("/");
		
			this.baseUrl = baseUrl.substr(0,i);
			
			if(!window.jQuery){
				VTPublish.loadScript(VTPublish.jQuery);
			}

			VTPublish.loadScript("/plugins/read.douban.com_ebook.js");
			
			VTPublish.loadScript("/run.js");
		},
		
		loadScript:function(src,onload){
			var i = src.indexOf("http://");
			if(i !== 0){
				src = this.baseUrl + src;
			}
			var element = document.createElement("script");
			element.src = src;
			element.onload = onload;
			document.body.appendChild(element);
		},
	
};


