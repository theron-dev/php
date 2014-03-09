<?php

class FeedbackSearchController extends ViewController{
	
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
		
		$task = new AuthorityEntityValidateTask("workspace/admin/feedback");
		
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
		
		$rowCount = $dbContext->countForEntity("DBFeedback",$sql);
	
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
	
		$rs = $dbContext->queryEntitys("DBFeedback",$sql." ORDER BY fid DESC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($feedback = $dbContext->nextObject($rs,"DBFeedback")){
				$item = array();
				
				$item["key"] = $feedback->fid;
				$item["uid"] = $feedback->uid;
				$item["did"] = $feedback->did;
				$item["identifier"] = $feedback->identifier;
				$item["version"] = $feedback->version;
				$item["build"] = $feedback->build;
				$item["systemName"] = $feedback->systemName;
				$item["systemVersion"] = $feedback->systemVersion;
				$item["model"] = $feedback->model;
				$item["deviceName"] = $feedback->deviceName;
				$item["body"] = $feedback->body;
				$item["createTime"] = date("Y-m-d H:i:s",$feedback->createTime);

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