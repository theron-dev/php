<?php

class AccountIndexController extends ViewController{
	
	
	private $logoutButton;

	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);

		$this->logoutButton = new Button("logout_button");
		
		$task = new AuthTask();
		
		try{
			$context->handle("AuthTask",$task);
		}
		catch(Exception $ex){
			getCurrentViewContext()->pushAttribute("window.location","href","login.php");
		}
		
		if(!$isPostback){
			$this->logoutButton->setClickAction(new Action($this,"Logout"));
			
			$task = new StatisticsTask();
			$task->target = STATISTICS_ACCOUNT."/index.php";
			$task->key = "pv";
			$context->handle("StatisticsTask",$task);
		}

	}
	
	public function onLogout(){
		
		$context = $this->getContext();
		
		$task = new LogoutTask();
		
		try{
			$context->handle("LogoutTask",$task);
		}
		catch(Exception $ex){
			
		}
		
		getCurrentViewContext()->pushAttribute("window.location","href","login.php");
	}

}

?>