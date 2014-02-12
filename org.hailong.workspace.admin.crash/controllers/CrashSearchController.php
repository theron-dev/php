<?php

class CrashSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 50;
	private $identifierText;
	private $versionText;
	private $buildText;
	private $searchButton;
	
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
		
		$this->identifierText = new TextView("identifierText");
		$this->versionText = new TextView("versionText");
		$this->buildText = new TextView("buildText");
		$this->searchButton = new Button("searchButton");
		
		$task = new AuthorityEntityValidateTask("workspace/admin/crash");
		
		try{
			$context->handle("AuthorityEntityValidateTask",$task);
		}
		catch(Exception $ex){
			getCurrentViewContext()->redirect("active.php");
			return ;
		}
		
		if(!$isPostback){
			$this->searchButton->setClickAction(new Action($this,"SearchAction"));
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
	
		$identifier = trim($this->identifierText->getText());
		$version = trim($this->versionText->getText());
		$build = trim($this->buildText->getText());
		
		$sql = "1=1";
		
		if($identifier){
			$sql .= " AND identifier LIKE '{$identifier}%'";
		}
		
		if($version){
			$sql .= " AND version LIKE '{$version}%'";
		}
		
		if($build){
			$sql .= " AND build LIKE '{$build}%'";
		}
		
		$rowCount = $dbContext->countForEntity("DBCrash",$sql);
	
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
	
		$rs = $dbContext->queryEntitys("DBCrash",$sql." ORDER BY cid DESC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($crash = $dbContext->nextObject($rs,"DBCrash")){
				$item = array();
				
				$item["key"] = $crash->cid;
				$item["identifier"] = $crash->identifier;
				$item["version"] = $crash->version;
				$item["build"] = $crash->build;
				$item["systemName"] = $crash->systemName;
				$item["systemVersion"] = $crash->systemVersion;
				$item["model"] = $crash->model;
				$item["deviceName"] = $crash->deviceName;
				$item["command"] = "<a href='export.php?cid={$crash->cid}'>下载</a>";
				$item["createTime"] = date("Y-m-d H:i:s",$crash->createTime);

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
	
	public function onSearchAction(){
		
		$this->loadContent();
	}
}

?>