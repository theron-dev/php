<?php

class DomainSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 30;

	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");

		$task = new AuthorityEntityValidateTask("workspace/admin/publish");
		
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
	
		$where = "1=1";
		
		$rowCount = $dbContext->countForEntity("DBPublishDomain",$where);
	
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
	
		$rs = $dbContext->queryEntitys("DBPublishDomain",$where." ORDER BY pdid ASC LIMIT {$offset},{$this->pageSize}");
	
		if($rs){
	
			while($domain = $dbContext->nextObject($rs,"DBPublishDomain")){
				
				$item = array();
				$item["key"] = $domain->pdid;
				$item["domain"] = "<a href='domain.php?pdid={$domain->pdid}' >".$domain->domain."</a>";
				$item["title"] = $domain->title;
				$item["body"] = $domain->body;
				$item["state"] = $domain->state == DBPublishDomainStateDisabled ? '禁用':'';
				$item["createTime"] = date("Y-m-d H:i:s",$domain->createTime);
				
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

		}
		else if($action == "edit"){

		}

	}
	
}

?>