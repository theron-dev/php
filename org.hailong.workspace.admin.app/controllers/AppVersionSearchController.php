<?php

class AppVersionSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 50;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
		
		$task = new AuthorityEntityValidateTask("workspace/admin/app");
		
		try{
			$context->handle("AuthorityEntityValidateTask",$task);
		}
		catch(Exception $ex){
			getCurrentViewContext()->redirect("active.php");
			return ;
		}
		
		if(!$isPostback){
			$this->searchPageListView->setSelectedChangeAction(new Action($this,"SearchPageAction"));
			$this->searchTable->setClickAction(new Action($this,"TableAction"));
			$this->loadContent();
		}
	}
	
	public function onSearchPageAction(){
		$this->loadContent();
	}
	
	public function loadContent(){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();
	
		$sql = "1=1";
		
		$appid = $context->getInputDataValue("appid");
		
		if($appid){
			$sql .= " AND appid=".intval($appid);
		}
		
		$rowCount = $dbContext->countForEntity("DBAppVersion",$sql);
	
		$pageIndex = $this->searchPageListView->getSelectedValue();
		if(!$pageIndex){
			$pageIndex = 1;
			$this->searchPageListView->setSelectedValue("1");
		}
	
		$pageCount = $rowCount % $this->pageSize ? intval($rowCount / $this->pageSize) + 1 : intval($rowCount / $this->pageSize);
	
		$items = array();
	
		for($i=0;$i<$pageCount;$i++){
			$items[] = array("value"=>($i +1),"text"=>"第".($i +1)."页");
		}
	
		$this->searchPageListView->setItems($items);
	
		$items = array();
	
		$offset = ($pageIndex -1) *  $this->pageSize;
	
		$rs = $dbContext->queryEntitys("DBAppVersion",$sql." ORDER BY avid DESC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($app = $dbContext->nextObject($rs,"DBAppVersion")){
				$item = array();
				$item["key"] = $app->avid;
				$item["appid"] = $app->appid;
				$item["platform"] = $app->platform;
				$item["version"] = $app->version;
				$item["content"] = $app->content;
				$item["uri"] = $app->uri;
				$item["updateLevel"] = $app->updateLevel;
				$item["isLastVersion"] = $app->isLastVersion;
				$command =  "<input type='button' value='修改' class='edit' key='{$app->avid}'></input>"
					."<input type='button' value='删除' action='delete' key='{$app->avid}'></input>";
				
				if(!$app->isLastVersion){
					$command .= "<input type='button' value='设为最新版' action='set' key='{$app->avid}'></input>";
				}
				
				$item["command"] = $command;
				$items[] = $item;
			}
			$dbContext->free($rs);
		}
	
		$this->searchTable->setItems($items);
	}
	
	public function onTableAction(){
		
		$key = $this->searchTable->getActionKey();
		$action = $this->searchTable->getAction();
		$actionData = $this->searchTable->getActionData();
		
		if($action == "add"){
			$task = new AppVersionCreateTask();
			$task->appid = isset($actionData["appid"]) ? intval($actionData["appid"]) : null;
			$task->platform = isset($actionData["platform"]) ? $actionData["platform"] : null;
			$task->version = isset($actionData["version"]) ? $actionData["version"] : null;
			$task->content = isset($actionData["content"]) ? $actionData["content"] : null;
			$task->uri = isset($actionData["uri"]) ? $actionData["uri"] : null;
			$task->updateLevel = isset($actionData["updateLevel"]) ? $actionData["updateLevel"] : null;
			
			try{
				$this->getContext()->handle("AppVersionCreateTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "edit"){
			$task = new AppVersionUpdateTask();
			$task->avid = $key;
			$task->content = isset($actionData["content"]) ? $actionData["content"] : null;
			$task->uri = isset($actionData["uri"]) ? $actionData["uri"] : null;
			$task->updateLevel = isset($actionData["updateLevel"]) ? $actionData["updateLevel"] : null;
			
			try{
				$this->getContext()->handle("AppVersionUpdateTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "set"){
			$task = new AppVersionSetLastTask();
			$task->avid= $key;
			try{
				$this->getContext()->handle("AppVersionSetLastTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "delete"){
			$task = new AppVersionRemoveTask();
			$task->avid= $key;
			try{
				$this->getContext()->handle("AppVersionSetLastTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
	}
}

?>