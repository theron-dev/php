<?php

class StatisticsSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 50;
	private $targetText;
	private $searchButton;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
		$this->targetText = new TextView("targetText");
		$this->searchButton = new Button("searchButton");
		
		
		$task = new AuthorityEntityValidateTask("workspace/admin/statistics");
		
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
	
		$target = trim($this->targetText->getText());
		
		$sql = "SELECT target, sum(pv) as pv,count(pv) as uv,count(source) as ipv,classifyTime FROM ".DBStatisticsUniversal::tableName();
		
		if($target){
			$sql .=" WHERE target LIKE ".$dbContext->parseValue($target.'%');
		}
		
		$sql .= " GROUP BY classifyTime,target ORDER BY classifyTime DESC ";
		
		$rowCount = $dbContext->countBySql($sql);
	
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
	
		$sql .= "LIMIT {$offset},{$this->pageSize}";
		
		$rs = $dbContext->query($sql);
	
		if($rs){
	
			while($row = $dbContext->next($rs)){
				$item = array();
				$item["target"] = $row["target"];
				$item["pv"] = $row["pv"];
				$item["uv"] = $row["uv"];
				$item["ipv"] = $row["ipv"];
				$item["classifyTime"] = date("Y-m-d",$row["classifyTime"]);
				$items[] = $item;
			}
			$dbContext->free($rs);
		}
	
		$this->searchTable->setItems($items);
	}
	
	public function onSearchAction(){
		$this->loadContent();
	}
	
	public function onTableAction(){
		
		$key = $this->searchTable->getActionKey();
		$action = $this->searchTable->getAction();
		$actionData = $this->searchTable->getActionData();
		
	}
}

?>