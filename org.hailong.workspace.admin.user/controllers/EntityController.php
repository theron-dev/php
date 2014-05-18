<?php

class EntityController extends ViewController{
	

	private $entityContent;
	
	private $entityTable;
	
	private $pageSize = 20;
	
	private $entityPageListView;

	private $dialogController;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->entityContent = new View("entity_content");
		
		$this->entityTable = new TableView("entity_table");
		
		$this->entityPageListView = new ListView("entity_page");
		
		$this->dialogController = new DialogController($context,$isPostback);
		
		if(!$isPostback){
			$this->entityTable->setClickAction(new Action($this,"EntityTableAction"));
			
			$this->entityPageListView->setSelectedChangeAction(new Action($this,"EntityPageAction"));
			
		}
	}
	
	
	public function loadContent(){
		$context = $this->getContext();
		$dbContext = $context->dbContext();
		
		$rowCount = $dbContext->countForEntity("DBAuthorityEntity");
		
		$pageIndex = $this->entityPageListView->getSelectedValue();
		if(!$pageIndex){
			$pageIndex = 1;
			$this->entityPageListView->setSelectedValue("1");
		}
		
		$pageCount = $rowCount % $this->pageSize ? intval($rowCount / $this->pageSize) + 1 : intval($rowCount / $this->pageSize);
		
		$items = array();
		
		for($i=0;$i<$pageCount;$i++){
			$items[] = array("value"=>($i +1),"text"=>"第".($i +1)."页");
		}
		
		$this->entityPageListView->setItems($items);
		
		$items = array();
		
		$offset = ($pageIndex -1) *  $this->pageSize;
		
		$rs = $dbContext->queryEntitys("DBAuthorityEntity","1=1 ORDER BY createTime DESC LIMIT {$offset},{$this->pageSize}");
		
		if($rs){
		
			while($entity = $dbContext->nextObject($rs,"DBAuthorityEntity")){
				$item = array();
				$item["key"] = $entity->aeid;
				$item["alias"] = $entity->alias;
				$item["title"] = $entity->title;
				$item["createTime"] = date("Y-m-d H:i:s",$entity->createTime);
				$item["command"] = "<input type='button' value='修改' class='edit' key='{$entity->aeid}'></input>"
					."<input type='button' value='删除' action='delete' key='{$entity->aeid}'></input>"
					."<input type='button' value='用户' action='user' key='{$entity->aeid}'></input>"
					."<input type='button' value='角色' action='role' key='{$entity->aeid}'></input>";
				$items[] = $item;
			}
			$dbContext->free($rs);
		}
		
		$this->entityTable->setItems($items);
	}
	
	
	public function onEntityTableAction(){
		$key = $this->entityTable->getActionKey();
		$action = $this->entityTable->getAction();
		$actionData = $this->entityTable->getActionData();
		
		if($action == "delete"){
			$task = new AuthorityEntityRemoveTask();
			$task->aeid= $key;
			try{
				$this->getContext()->handle("AuthorityEntityRemoveTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "add"){
			$task = new AuthorityEntityAddTask();
			$task->alias = isset($actionData["alias"]) ? $actionData["alias"] : null;
			$task->title = isset($actionData["title"]) ? $actionData["title"] : null;

			try{
				$this->getContext()->handle("AuthorityEntityAddTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "edit"){
			$task = new AuthorityEntityUpdateTask();
			$task->aeid = $key;
			$task->alias = isset($actionData["alias"]) ? $actionData["alias"] : null;
			$task->title = isset($actionData["title"]) ? $actionData["title"] : null;
		
			try{
				$this->getContext()->handle("AuthorityEntityUpdateTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "user"){
		
			$this->dialogController->setDialog("user");
			$this->dialogController->setSource("entity");
			$this->dialogController->setArgument($key);
				
			$this->dialogController->loadContent();
		}
		else if($action == "role"){
		
			$this->dialogController->setDialog("role");
			$this->dialogController->setSource("entity");
			$this->dialogController->setArgument($key);
				
			$this->dialogController->loadContent();
		}
	}

	public function onEntityPageAction(){
		$this->loadContent();
	}
	
	public function setHidden($hidden){
		$this->entityContent->setHidden($hidden);
	}
}

?>