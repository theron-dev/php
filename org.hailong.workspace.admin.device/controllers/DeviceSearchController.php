<?php

class DeviceSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 50;
	private $rowCountLabel;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
		$this->rowCountLabel = new Label("rowCount");
		
		$task = new AuthorityEntityValidateTask("workspace/admin/device");
		
		try{
			$context->handle("AuthorityEntityValidateTask",$task);
		}
		catch(Exception $ex){
			getCurrentViewContext()->redirect("active.php");
			return ;
		}
		
		if(!$isPostback){
			$this->searchPageListView->setSelectedChangeAction(new Action($this,"SearchPageAction"));
			
			$this->loadContent();
		}
	}
	
	public function onSearchPageAction(){
		$this->loadContent();
	}
	
	public function loadContent(){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();
	
		$rowCount = $dbContext->countForEntity("DBDevice");
	
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
	
		$rs = $dbContext->queryEntitys("DBDevice","1=1 ORDER BY createTime DESC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($device = $dbContext->nextObject($rs,"DBDevice")){
				$item = array();
				$item["key"] = $device->did;
				$item["type"] = $device->type;
				$item["unique"] = $device->unique;
				$item["name"] = $device->name;
				$item["model"] = $device->model;
				$item["systemName"] = $device->systemName;
				$item["systemVersion"] = $device->systemVersion;
				$item["createTime"] = date("Y-m-d H:i:s",$device->createTime);
				
				$items[] = $item;
			}
			$dbContext->free($rs);
		}
	
		$this->searchTable->setItems($items);
	}
	
}

?>