<?php

class AppDeviceSearchController extends ViewController{
	
	private $searchTable;
	private $searchPageListView;
	private $pageSize = 50;
	private $rowCountLabel;
	private $rowPushCountLabel;
	
	private $didText;
	private $versionText;
	private $buildText;
	private $searchButton;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->searchPageListView = new ListView("search_page");
		$this->rowCountLabel = new Label("rowCount");
		$this->rowPushCountLabel = new Label("rowPushCount");
		
		$this->didText = new TextView("didText");
		$this->versionText = new TextView("versionText");
		$this->buildText = new TextView("buildText");
		
		$this->searchButton = new Button("searchButton");
		
		$task = new AuthorityEntityValidateTask("workspace/admin/app");
		
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
	
	public function onSearchAction(){
		$this->loadContent();
	}
	
	public function loadContent(){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();
	
		$sql = "1=1";
		
		$appid = $context->getInputDataValue("appid");
		
		if($appid){
			$sql .= " AND appid=".intval($appid);
		}
		
		$did = trim($this->didText->getText());
		
		if($did){
			$sql .= " AND did=".intval($did);
		}
		
		$version = trim($this->versionText->getText());
		
		if($version){
			$sql .= " AND version=".$dbContext->parseValue( $version);
		}
		
		$build = trim($this->buildText->getText());
		
		if($build){
			$sql .= " AND build=".$dbContext->parseValue( $build);
		}
		
		$rowCount = $dbContext->countForEntity("DBAppDevice",$sql);
	
		$this->rowCountLabel->setText("总设备数: ".$rowCount);
		
		$rowPushCount = $dbContext->countForEntity("DBAppDevice",$sql." AND NOT ISNULL(token)");
		
		$this->rowPushCountLabel->setText("可推送设备数: ".$rowPushCount);
		
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
	
		$rs = $dbContext->queryEntitys("DBAppDevice",$sql." ORDER BY updateTime DESC LIMIT {$offset},{$this->pageSize}");
	
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