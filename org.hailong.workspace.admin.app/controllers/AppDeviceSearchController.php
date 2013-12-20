<?php

class AppDeviceSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 50;
	private $rowCountLabel;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
		$this->rowCountLabel = new Label("rowCount");
		
		$task = new AuthorityEntityValidateTask("workspace/admin/app");
		
		try{
			$context->handle("AuthorityEntityValidateTask",$task);
		}
		catch(Exception $ex){
			getCurrentViewContext()->pushAttribute("window.location","href","active.php");
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
	
		$rowCount = $dbContext->countForEntity("DBAppDevice");
	
		$this->rowCountLabel->setText("总设备数: ".$rowCount);
		
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
	
		$rs = $dbContext->queryEntitys("DBAppDevice","1=1 ORDER BY updateTime DESC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($app = $dbContext->nextObject($rs,"DBAppDevice")){
				$item = array();
				$item["key"] = $app->adid;
				$item["appid"] = $app->appid;
				$item["did"] = $app->did;
				$item["token"] = $app->token;
				$item["version"] = $app->version;
				$item["build"] = $app->build;
				$item["updateTime"] = date("Y-m-d H:i:s",$app->updateTime);
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
		
		
	}
}

?>