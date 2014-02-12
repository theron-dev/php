<?php

class AppSearchController extends ViewController{
	
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
	
		$rowCount = $dbContext->countForEntity("DBApp");
	
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
	
		$rs = $dbContext->queryEntitys("DBApp","1=1 ORDER BY createTime DESC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($app = $dbContext->nextObject($rs,"DBApp")){
				$item = array();
				$item["key"] = $app->appid;
				$item["title"] = $app->title;
				$item["description"] = $app->description;
				$item["createTime"] = date("Y-m-d H:i:s",$app->createTime);
				$item["command"] = "<input type='button' value='修改' class='edit' key='{$app->appid}'></input>"
					."<input type='button' value='删除' action='delete' key='{$app->appid}'></input>"
					."<input type='button' value='设备' onclick=\"window.location.href='device.php?appid={$app->appid}'\"></input>"
					."<input type='button' value='版本' onclick=\"window.location.href='version.php?appid={$app->appid}'\"></input>";
				
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
			$task = new AppCreateTask();
			$task->appid = isset($actionData["key"]) && intval($actionData["key"]) >0 ? intval($actionData["key"]) : null;
			$task->title = isset($actionData["title"]) ? $actionData["title"] : null;
			$task->description = isset($actionData["description"]) ? $actionData["description"] : null;
			
			try{
				$this->getContext()->handle("AppCreateTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "edit"){
			$task = new AppUpdateTask();
			$task->appid = $key;
			$task->title = isset($actionData["title"]) ? $actionData["title"] : null;
			$task->description = isset($actionData["description"]) ? $actionData["description"] : null;
			
			try{
				$this->getContext()->handle("AppUpdateTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "delete"){
			$task = new AppRemoveTask();
			$task->appid= $key;
			try{
				$this->getContext()->handle("AppRemoveTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
	}
}

?>