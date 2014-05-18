<?php

class RoleController extends ViewController{
	
	private $roleContent;

	private $roleTable;
	
	private $pageSize = 20;
	
	private $rolePageListView;
	
	private $dialogController;

	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
	
		$this->roleContent = new View("role_content");
		
		$this->roleTable = new TableView("role_table");

		$this->rolePageListView = new ListView("role_page");
		
		$this->dialogController = new DialogController($context,$isPostback);
		
		if(!$isPostback){
			
			$this->roleTable->setClickAction(new Action($this,"RoleTableAction"));
			
			$this->rolePageListView->setSelectedChangeAction(new Action($this,"RolePageAction"));
			
		}
	}
	
	
	public function loadContent(){
		$context = $this->getContext();
		$dbContext = $context->dbContext();
		
		$rowCount = $dbContext->countForEntity("DBAuthorityRole");
		
		$pageIndex = $this->rolePageListView->getSelectedValue();
		if(!$pageIndex){
			$pageIndex = 1;
			$this->rolePageListView->setSelectedValue("1");
		}
		
		$pageCount = $rowCount % $this->pageSize ? intval($rowCount / $this->pageSize) + 1 : intval($rowCount / $this->pageSize);
		
		$items = array();
		
		for($i=0;$i<$pageCount;$i++){
			$items[] = array("value"=>($i +1),"text"=>"第".($i +1)."页");
		}
		
		$this->rolePageListView->setItems($items);
		
		$items = array();
		
		$offset = ($pageIndex -1) *  $this->pageSize;
		
		$rs = $dbContext->queryEntitys("DBAuthorityRole","1=1 ORDER BY createTime DESC LIMIT {$offset},{$this->pageSize}");
		if($rs){
		
			while($role = $dbContext->nextObject($rs,"DBAuthorityRole")){
				$item = array();
				$item["key"] = $role->arid;
				$item["name"] = $role->name;
				$item["title"] = $role->title;
				$item["description"] = $role->description;
				$item["createTime"] = date("Y-m-d H:i:s",$role->createTime);
				$item["command"] = "<input type='button' value='修改' class='edit' key='{$role->arid}'></input>"
					."<input type='button' value='删除' action='delete' key='{$role->arid}'></input>"
					."<input type='button' value='用户' action='user' key='{$role->arid}'></input>"
					."<input type='button' value='实体' action='entity' key='{$role->arid}'></input>";
				
				$items[] = $item;
			}
			$dbContext->free($rs);
		}
		
		$this->roleTable->setItems($items);
	}
	

	public function onLoadView(){
		
	}

	
	public function onRoleTableAction(){
		$key = $this->roleTable->getActionKey();
		$action = $this->roleTable->getAction();
		$actionData = $this->roleTable->getActionData();
		
		if($action == "delete"){
			$task = new AuthorityRoleRemoveTask();
			$task->arid= $key;
			try{
				$this->getContext()->handle("AuthorityRoleRemoveTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "add"){
			$task = new AuthorityRoleAddTask();
			$task->name = isset($actionData["name"]) ? $actionData["name"] : null;
			$task->title = isset($actionData["title"]) ? $actionData["title"] : null;
			$task->description = isset($actionData["description"]) ? $actionData["description"] : null;
			
			try{
				$this->getContext()->handle("AuthorityRoleAddTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "edit"){
			$task = new AuthorityRoleUpdateTask();
			$task->arid = $key;
			$task->name = isset($actionData["name"]) ? $actionData["name"] : null;
			$task->title = isset($actionData["title"]) ? $actionData["title"] : null;
			$task->description = isset($actionData["description"]) ? $actionData["description"] : null;
				
			try{
				$this->getContext()->handle("AuthorityRoleUpdateTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "user"){
				
			$this->dialogController->setDialog("user");
			$this->dialogController->setSource("role");
			$this->dialogController->setArgument($key);
				
			$this->dialogController->loadContent();

		}
		else if($action == "entity"){
			
			$this->dialogController->setDialog("entity");
			$this->dialogController->setSource("role");
			$this->dialogController->setArgument($key);
			
			$this->dialogController->loadContent();
		}
	}
	
	
	public function onRolePageAction(){
		$this->loadContent();
	}
	
	public function setHidden($hidden){
		$this->roleContent->setHidden($hidden);
	}
}

?>