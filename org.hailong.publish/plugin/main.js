
VTPublish = {
		
		jQuery:"/js/jquery-2.0.2.min.js",
		Plugins:[],
		
		run:function(script){
			
			var baseUrl = script.src;
			var i = baseUrl.lastIndexOf("/");
		
			this.baseUrl = baseUrl.substr(0,i);
			
			this.show();
			
			if(!window.jQuery){
				VTPublish.loadScript(VTPublish.jQuery);
			}

			VTPublish.loadScript("/plugins/read.douban.com_ebook.js");
			
			VTPublish.loadScript("/plugins/run.js");
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
			document.body.appendChild(element);
		},
	
};


