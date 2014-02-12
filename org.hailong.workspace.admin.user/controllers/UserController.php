<?php

class UserController extends ViewController{
	
	private $toolbar;
	
	private $accountController;
	private $roleController;
	private $entityController;

	private $dialogController;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->toolbar = new ListView("toolbar");
		
		$this->accountController = new AccountController($context,$isPostback);
		$this->roleController=  new RoleController($context,$isPostback);
		$this->entityController = new EntityController($context,$isPostback);
		
		$this->dialogController = new DialogController($context,$isPostback);
		
		
		$task = new AuthorityEntityValidateTask("workspace/admin/user");
		
		try{
			$context->handle("AuthorityEntityValidateTask",$task);
		}
		catch(Exception $ex){
			getCurrentViewContext()->redirect("active.php");
			return ;
		}
		if(!$isPostback){
			$this->toolbar->setSelectedChangeAction(new Action($this,"Selected"));
			
			$selectedValue = $this->toolbar->getSelectedValue();
			
			if(!$selectedValue){
				$selectedValue = "user";
				$this->toolbar->setSelectedValue($selectedValue);
			}
			
			$this->changeContent();
			
			$this->dialogController->setDialog("");
		}
	}
	
	
	public function changeContent(){
		$selectedValue = $this->toolbar->getSelectedValue();
		$this->accountController->setHidden(true);
		$this->roleController->setHidden(true);
		$this->entityController->setHidden(true);
		
		if($selectedValue == "role"){
			$this->roleController->setHidden(false);
			$this->roleController->loadContent();
		}
		else if($selectedValue == "entity"){
			$this->entityController->setHidden(false);
			$this->entityController->loadContent();
		}
		else{
			$this->accountController->setHidden(false);
			$this->accountController->loadContent();
		}
	}

	public function onLoadView(){
		
	}

	public function onSelected(){
		$this->changeContent();
	}
	
	public function onUserTableAction(){
		$this->accountController->onUserTableAction();
	}
	
	public function onRoleTableAction(){
		$this->roleController->onRoleTableAction();
	}
	
	public function onEntityTableAction(){
		$this->entityController->onEntityTableAction();
	}
	
	public function onUserPageAction(){
		$this->accountController->onUserPageAction();
	}
	
	public function onRolePageAction(){
		$this->roleController->onRolePageAction();
	}
	
	public function onEntityPageAction(){
		$this->entityController->onEntityPageAction();
	}
	
	public function onEntityListSelected(){
		$this->authController->onEntityListSelected();
	}
	
	public function onDialogTableAction(){
		$this->dialogController->onDialogTableAction();
	}
	
	public function onDialogPageAction(){
		$this->dialogController->onDialogPageAction();
	}
}

?>