<?php

class LogoutController extends ViewController{
	
	private $logoutButton;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		$this->logoutButton = new Button("logout_button");
		$this->logoutInfoButton = new Button("logout_info");
		
	}

	public function onLoadView(){
		$action = new Action($this,"Logout");
		$this->logoutButton->setClickAction($action);
	}
	

	public function onLogout(){
		$context = $this->getContext();
		$task = new LogoutTask();
		$context->fillTask($task);
			
		$context->handle("LogoutTask",$task);
		
		global $library;
		$loginUrl = require("$library/org.hailong.configs/login_url.php");
		$loginUrl =  $loginUrl.urlencode("http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["REQUEST_URI"]));
			
		getCurrentViewContext()->redirect($loginUrl);
	}
	
}

?>