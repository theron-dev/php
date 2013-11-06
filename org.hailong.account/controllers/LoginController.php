<?php

class LoginController extends ViewController{
	
	private $messageLabel;
	private $accountTextView;
	private $passwordTextView;
	private $loginButton;
	
	private $loginView;
	private $sessionView;
	
	private $sessionTable;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);

		$this->loginView = new View("login");
		$this->sessionView = new View("login_session");
		$this->sessionTable = new TableView("login_session_table");
		
		$this->messageLabel = new Label("login_message");
		$this->accountTextView = new TextView("login_account");
		$this->passwordTextView = new TextView("login_password");
		$this->loginButton = new Button("login_login");
		
		if(!$isPostback){
			$action = new Action($this,"Login");
			$action->setSource("auth-account","login_account","text");
			$action->setSource("auth-password","login_password","text");
			$this->loginButton->setClickAction($action);
			
			$this->sessionTable->setClickAction(new Action($this,"SessionAction"));
			
			
			$this->loginView->setHidden(false);
			$this->sessionView->setHidden(true);
		}
	}

	public function onLoadView(){
		$this->accountTextView->setText("admin");
	}
	

	public function onLogin(){
		$context = $this->getContext();
		try{
			$task = new LoginTask();
			$context->fillTask($task);
				
			$context->handle("LoginTask",$task);

			$this->messageLabel->setHidden(true);
	
			$this->passwordTextView->setText("");
			
			$dbContext = $context->dbContext();
	
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			} 
			
			$auth = $context->getInternalDataValue("auth");
			$count = $dbContext->countForEntity("DBUserViewState","uid={$auth} GROUP BY session");
			
			if($count==0){
				$_SESSION["view-state-session"] = session_id();
				getCurrentViewContext()->pushAttribute("window.location","href","index.php");
			}
			else{
				$this->loginView->setHidden(true);
				$this->sessionView->setHidden(false);
				$this->loadSessionContent();
			}
			
			return true;
		}
		catch(Exception $ex){
			$this->messageLabel->setText($ex->getMessage());
			$this->messageLabel->setHidden(false);
			$this->passwordTextView->setText("");
		}
		
		return false;
	}
	
	public function loadSessionContent(){
		
		$context = $this->getContext();
		
		$task = new AuthTask();
		
		$items = array();
		
		try{
			$context->handle("AuthTask",$task);
			$auth = $context->getInternalDataValue("auth");
			
			$dbContext = $context->dbContext();
	
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
		
			
			$rs = $dbContext->queryEntitys("DBUserViewState","uid={$auth} GROUP BY session");
			
			if($rs){
				while($viewState = $dbContext->nextObject($rs,"DBUserViewState")){
					$item = array();
					$item["session"] = $viewState->session;
					$item["source"] = $viewState->saveSource;
					$item["time"] = date("Y-m-d H:i:s",$viewState->saveTime);
					$item["command"] = "<input type='button' action='join' key='{$viewState->session}' value='进入' ></input><input type='button' action='remove' key='{$viewState->session}' value='删除' ></input> ";
					$items[] = $item;
				}
				$dbContext->free($rs);
			}
		}
		catch(Exception $ex){

		}
		
		$this->sessionTable->setItems($items);
	}
	
	public function onSessionAction(){
		$action = $this->sessionTable->getAction();
		$key = $this->sessionTable->getActionKey();
		
		$context = $this->getContext();
		
		if($action == "join"){
			$_SESSION["view-state-session"] = $key;
			getCurrentViewContext()->pushAttribute("window.location","href","index.php");
		}
		else if($action == "remove"){
			$task = new UserViewStateClearTask();
			$task->session = $key;
			
			try{
				$context->handle("UserViewStateClearTask",$task);
	
				$this->loadSessionContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex.getMessage());
			}
		}
		else if($action == "join-new"){
			$task = new UserViewStateClearTask();
			$task->session = session_id();
			try{
				$context->handle("UserViewStateClearTask",$task);
			}
			catch(Exception $ex){
			}
			$_SESSION["view-state-session"] = session_id();
			getCurrentViewContext()->pushAttribute("window.location","href","index.php");
		}
		else if($action == "join-new-clear"){
			$task = new UserViewStateClearTask();	
			try{
				$context->handle("UserViewStateClearTask",$task);
			
				$_SESSION["view-state-session"] = session_id();
				getCurrentViewContext()->pushAttribute("window.location","href","index.php");
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex.getMessage());
			}
		}
	}
	
}

?>