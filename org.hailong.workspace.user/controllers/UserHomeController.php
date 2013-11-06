<?php

class UserHomeController extends ViewController{
	
	private $userInfoController;
	private $userPasswordChangeController;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$viewContext = new ViewContext("user",getCurrentViewContext());
		
		pushViewContext($viewContext);
		
		$this->userInfoController = new UserBaseInfoController($context,$isPostback);
		$this->userPasswordChangeController = new UserPasswordChangeController($context,$isPostback);
		
		popViewContext();
		
		if(!$isPostback){

		}
	}
	
	
}

?>