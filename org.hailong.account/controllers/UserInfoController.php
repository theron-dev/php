<?php

class UserInfoController extends ViewController{
	
	private $userInfoButton;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		$this->userInfoButton = new Button("user_info_button");
		
		$dbContext = $context->dbContext();
		$user = $dbContext->get("DBAccount",array("uid"=>$context->getInternalDataValue("auth")));
		if($user){
			if($user->title !=$this->userInfoButton->getTitle()){
				$this->userInfoButton->setTitle($user->title);
			}
		}
	}

	public function onLoadView(){

	}

	
}

?>