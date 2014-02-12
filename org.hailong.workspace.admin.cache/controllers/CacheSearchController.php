<?php

class CacheSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 50;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
	
		$task = new AuthorityEntityValidateTask("workspace/admin/cache");
		
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
	
		$rowCount = $dbContext->countForEntity("DBCache");
	
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
	
		$rs = $dbContext->queryEntitys("DBCache"," 1=1 ORDER BY createTime ASC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($cache = $dbContext->nextObject($rs,"DBCache")){
				$item = array();
				$item["key"] = $cache->cid;
				$item["path"] = $cache->path;
				$item["value"] = $cache->value;
				$item["updateTime"] =  date("Y-m-d H:i:s",$cache->updateTime);
				$item["createTime"] = date("Y-m-d H:i:s",$cache->createTime);
				$item["command"] = "<input type='button' value='删除' action='delete' key='{$cache->cid}'></input>";
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
		
		if($action == "delete"){
			
			$task = new CacheRemoveTask();
			$task->cid = $key;
			try{
				$this->getContext()->handle("CacheRemoveTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}

		}
		
		if($action == "cleanup"){
				
			$task = new CacheCleanupTask();

			try{
				$this->getContext()->handle("CacheCleanupTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		
		}
		
	}
}

?>