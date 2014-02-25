<?php

class TagSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 30;
	private $tagText;
	private $searchButton;
	private $allButton;
	private $rowCountText;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
		$this->tagText = new TextView("tagText");
		$this->searchButton = new Button("searchButton");
		$this->allButton = new Button("allButton");
		$this->rowCountText = new Label("row_count");
		
		$task = new AuthorityEntityValidateTask("workspace/admin/tag");
		
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
		
		$where = "1=1";
		if($tag != ""){
			$where .=" AND tag='{$tag}'";
		}
		
		$rowCount = $dbContext->countForEntity("DBTag",$where);
		
		$this->rowCountText->setText($rowCount);
	
		$pageIndex = $this->searchPageListView->getSelectedValue();
		if(!$pageIndex){
			$pageIndex = 1;
			$this->searchPageListView->setSelectedValue("1");
		}
	
		$pageCount = $rowCount % $this->pageSize ? intval($rowCount / $this->pageSize) + 1 : intval($rowCount / $this->pageSize);
	
		$pageBegin = $pageIndex - 50;
		$pageEnd = $pageIndex + 50;
		
		if($pageBegin <0){
			$pageBegin = 0;
		}
		
		if($pageEnd > $pageCount){
			$pageEnd = $pageCount;
		}

		$items = array();
		
		if($pageBegin >0){
			$items[] = array("value"=>1,"text"=>"第1页");
		}
		
		for($i=$pageBegin;$i<$pageEnd;$i++){
			$items[] = array("value"=>($i +1),"text"=>"第".($i +1)."页");
		}
		
		if($pageEnd < $pageCount){
			$items[] = array("value"=>$pageCount,"text"=>"第".$pageCount."页");
		}
	
		$this->searchPageListView->setItems($items);
	
		$items = array();
	
		$offset = ($pageIndex -1) *  $this->pageSize;
	
		
		$rs = $dbContext->queryEntitys("DBTag",$where." ORDER BY weight DESC,createTime ASC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($tag = $dbContext->nextObject($rs,"DBTag")){
				$item = array();
				$item["key"] = $tag->tid;
				$item["tag"] = $tag->tag;
				$item["weight"] = $tag->weight;
				$item["updateTime"] = date("Y-m-d H:i:s",$tag->updateTime);
				$item["createTime"] = date("Y-m-d H:i:s",$tag->createTime);
				$item["command"] = "<input type='button' value='访问' action='assign' key='{$tag->tag}'></input>";
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
			$task = new TagAssignTask();
			$task->tag = isset($actionData["tag"]) ? $actionData["tag"] : null;
			
			try{
				$this->getContext()->handle("TagAssignTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}
		else if($action == "assign"){
			$task = new TagAssignTask();
			$task->inc = 1;
			$task->tag= $key;
			try{
				$this->getContext()->handle("TagAssignTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
			}
		}

	}
	
	public function onSearchAction(){
		$this->loadContent();
	}
	
	public function onAllAction(){
		$this->tagText->setText("");
		$this->searchPageListView->setSelectedValue("1");
		$this->loadContent();
	}
	
	
}

?>