<?php

class CoinSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 50;
	private $sourceText;
	private $searchButton;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
		$this->sourceText = new TextView("sourceText");
		$this->searchButton = new Button("searchButton");
		
		$task = new AuthorityEntityValidateTask("workspace/admin/coin");
		
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
			$this->searchButton->setClickAction(new Action($this,"SearchAction"));
			
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
		
		$source = $this->sourceText->getText();
		
		if($source){
			$sql .= " AND `source` LIKE '".$source."%'";
		}
		
		$rowCount = $dbContext->countForEntity("DBCoinIncome",$sql);
	
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
	
		$rs = $dbContext->queryEntitys("DBCoinIncome","{$sql} ORDER BY ciid DESC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($data = $dbContext->nextObject($rs,"DBCoinIncome")){
				$item = array();
				$item["key"] = $data->ciid;
				$item["uid"] = $data->uid;
				$item["coin"] = $data->coin;
				$item["source"] = $data->source;
				$item["sid"] = $data->sid;
				$item["stype"] = $data->stype;
				$item["updateTime"] = date("Y-m-d H:i:s",$data->updateTime);
				$item["createTime"] = date("Y-m-d H:i:s",$data->createTime);
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
		
		
	}
	
	public function onSearchAction(){
		$this->loadContent();
	}
}

?>