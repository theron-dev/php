<?php

class AsyncTaskSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 50;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
		
		$task = new AuthorityEntityValidateTask("workspace/admin/asynctask");
		
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
	
		$rowCount = $dbContext->countForEntity("DBAsyncTask");
	
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
	
		$rs = $dbContext->queryEntitys("DBAsyncTask","1=1 ORDER BY atid ASC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($asyncTask = $dbContext->nextObject($rs,"DBAsyncTask")){
				$item = array();
				$item["key"] = $asyncTask->atid;
				$item["config"] = $asyncTask->config;
				$item["taskType"] = $asyncTask->taskType;
				$item["taskClass"] = $asyncTask->taskClass;
				$item["state"] = DBAsyncTask::getStateTitle($asyncTask->state);
				$item["data"] = $asyncTask->data;
				$item["rank"] = $asyncTask->rank;
				$item["results"] = $asyncTask->results;
				$item["createTime"] = date("Y-m-d H:i:s",$asyncTask->createTime);
				$item["command"] = "<input type='button' value='重置' action='reset' key='{$asyncTask->atid}'>";
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
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();
		if($action == "reset"){
				
			$task = new AsyncResetTask();
			$task->atid = $key;
			
			try{
				$this->getContext()->handle("AsyncResetTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "cleanup"){
			
			$task = new AsyncCleanupTask();
			
			try{
				$this->getContext()->handle("AsyncCleanupTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		
	}
}

?>