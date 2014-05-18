<?php

class AccountController extends ViewController{
	
	private $userContent;
	
	private $userTable;
	
	private $pageSize = 20;
	
	private $userPageListView;

	private $dialogController;
	
	private $userCountTextView;

	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->userContent = new View("user_content");
		
		$this->userTable = new TableView("user_table");
		
		$this->userPageListView = new ListView("user_page");
		
		$this->userCountTextView = new TextView("user_count");
		
		$this->dialogController = new DialogController($context,$isPostback);
		
		if(!$isPostback){
			$this->userTable->setClickAction(new Action($this,"UserTableAction"));
			$this->userPageListView->setSelectedChangeAction(new Action($this,"UserPageAction"));
		}
	}
	
	public function loadContent(){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();
		
		$where = "state <> ".AccountStateGenerated;
		
		$rowCount = $dbContext->countForEntity("DBAccount",$where);
		
		$pageIndex = $this->userPageListView->getSelectedValue();
		if(!$pageIndex){
			$pageIndex = 1;
			$this->userPageListView->setSelectedValue("1");
		}
		
		$this->userCountTextView->setText("总用户数: ".$rowCount);
		
		$pageCount = $rowCount % $this->pageSize ? intval($rowCount / $this->pageSize) + 1 : intval($rowCount / $this->pageSize);
		
		$items = array();
		
		for($i=0;$i<$pageCount;$i++){
			$items[] = array("value"=>($i +1),"text"=>"第".($i +1)."页");
		}
		
		$this->userPageListView->setItems($items);
		
		$items = array();
		
		$offset = ($pageIndex -1) *  $this->pageSize;

		$rs = $dbContext->queryEntitys("DBAccount","{$where} ORDER BY uid ASC LIMIT {$offset},{$this->pageSize}");
		
		if($rs){
			
			while($account = $dbContext->nextObject($rs,"DBAccount")){
				
				$item = array();
				$item["key"] = $account->uid;
				$item["account"] = $account->account;
				$item["title"] = $account->title;
				$item["tel"] = $account->tel;
				$item["email"] = $account->email;
				$item["loginTime"] = $account->loginTime ? date("Y-m-d H:i:s",$account->loginTime):"";
				$item["createTime"] = date("Y-m-d H:i:s",$account->createTime);
				$item["command"] = "<input type='button' value='角色' action='role' key='{$account->uid}'>"
					."<input type='button' value='实体' action='entity' key='{$account->uid}'>";
				
				$t = new AccountInfoGetTask();
				
				$t->keys = array(AccountInfoKeyNick);
				$t->uid = $account->uid;
				
				$context->handle("AccountInfoGetTask",$t);
				
				if(isset($t->infos[AccountInfoKeyNick]["value"])){
					$item["nick"] = $t->infos[AccountInfoKeyNick]["value"];
				}
				
				$items[] = $item;
			}
			$dbContext->free($rs);
		}
		
		$this->userTable->setItems($items);
		
	}
	
	public function onUserTableAction(){
		$key = $this->userTable->getActionKey();
		$action = $this->userTable->getAction();
		$actionData = $this->userTable->getActionData();
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();
		if($action == "role"){
			
			$this->dialogController->setDialog("role");
			$this->dialogController->setSource("user");
			$this->dialogController->setArgument($key);
			
			$this->dialogController->loadContent();
		}
		else if($action == "entity"){
			
			$this->dialogController->setDialog("entity");
			$this->dialogController->setSource("user");
			$this->dialogController->setArgument($key);
			
			$this->dialogController->loadContent();
		}
	}
	
	
	public function onUserPageAction(){
		
		$this->loadContent();
	}

	public function setHidden($hidden){
		$this->userContent->setHidden($hidden);
	}
}

?>