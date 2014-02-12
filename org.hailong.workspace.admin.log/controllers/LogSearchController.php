<?php

class LogSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 30;
	private $tagText;
	private $sourceText;
	private $searchButton;
	private $allButton;
	private $clearButton;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
		$this->tagText = new TextView("tagText");
		$this->sourceText = new TextView("sourceText");
		$this->searchButton = new Button("searchButton");
		$this->allButton = new Button("allButton");
		$this->clearButton = new Button("clearButton");
		
		$task = new AuthorityEntityValidateTask("workspace/admin/log");
		
		try{
			$context->handle("AuthorityEntityValidateTask",$task);
		}
		catch(Exception $ex){
			getCurrentViewContext()->redirect("active.php");
			return ;
		}
		
		if(!$isPostback){
			$this->searchButton->setClickAction(new Action($this,"SearchAction"));
			$this->allButton->setClickAction(new Action($this,"AllAction"));
			$this->clearButton->setClickAction(new Action($this,"ClearAction"));
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
	
		$tag = trim($this->tagText->getText());
		$source = trim($this->sourceText->getText());
		
		$where = "1=1";
		if($tag != ""){
			$where .=" AND tag='{$tag}'";
		}
		if($source != ""){
			$where .=" AND source='{$source}'";
		}
		
		$rowCount = $dbContext->countForEntity("DBLog",$where);
	
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
	
		$rs = $dbContext->queryEntitys("DBLog",$where." ORDER BY createTime DESC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($log = $dbContext->nextObject($rs,"DBLog")){
				$item = array();
				$item["key"] = $log->lid;
				$item["source"] = "<a href='javascript:;' action='source' key='{$log->source}' >".$log->source."</a>";
				$item["tag"] = "<a href='javascript:;' action='tag' key='{$log->tag}' >".$log->tag."</a>";
				$item["level"] = $log->level;
				$item["body"] = $log->body;
				$item["createTime"] = date("Y-m-d H:i:s",$log->createTime);
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
		
		if($action == "tag"){
			$this->tagText->setText($key);
			$this->loadContent();
		}
		else if($action == "source"){
			$this->sourceText->setText($key);
			$this->loadContent();
		}

	}
	
	public function onSearchAction(){
		$this->loadContent();
	}
	
	public function onAllAction(){
		$this->tagText->setText("");
		$this->sourceText->setText("");
		$this->loadContent();
	}
	
	public function onClearAction(){
		$context = $this->getContext();
		$task = new LogClearTask();
		
		try{
			$context->handle("LogClearTask",$task);
			
			$this->loadContent();
		}
		catch(Exception $ex){
			getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
		}
		
	}
}

?>