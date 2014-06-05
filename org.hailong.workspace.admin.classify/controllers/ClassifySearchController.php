<?php

class ClassifySearchController extends ViewController{
	
	private $searchTable;
	private $actionView;
	private $pcidText;
	private $targetSelect;
	
	private $dialog;
	private $editForm;
	private $cancelButton;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->searchTable = new TableView("search_table");
		$this->actionView = new ActionView("actionView");
		$this->pcidText = new TextView("pcidText");
		$this->targetSelect = new ListView("targetSelect");
		
		$this->dialog = new View("dialog");
		$this->editForm = new Form("editForm");
		$this->cancelButton = new Button("cancelButton");
		
		$task = new AuthorityEntityValidateTask("workspace/admin/classify");
		
		try{
			$context->handle("AuthorityEntityValidateTask",$task);
		}
		catch(Exception $ex){
			getCurrentViewContext()->redirect("active.php");
			return ;
		}
		
		if(!$isPostback){
			$this->actionView->setClickAction(new Action($this,"Action"));
			$this->searchTable->setClickAction(new Action($this,"TableAction"));
			$this->targetSelect->setSelectedChangeAction(new Action($this,"TargetAction"));
			$this->editForm->setSubmitAction(new Action($this,"SubmitAction"));
			$this->cancelButton->setClickAction(new Action($this,"CancelAction"));
			
			$this->loadContent();
			$this->targetSelect->setSelectedValue("0");
		}
	}
	
	public function onSearchPageAction(){
		$this->loadContent();
	}
	
	public function onSubmitAction(){
		
		$context = $this->getContext();
		$fields = $this->editForm->getFields();
		
		$cid = isset($fields["cid"]) ? intval($fields["cid"]) : null;
		$logo = $context->getInputDataValue("logo");
		
		if($cid !== null && $logo !== null){
			
			$t = new ClassifyUpdateTask();
			$t->cid = $cid;
			$t->logo = $logo;
			
			$context->handle("ClassifyUpdateTask",$t);
			
		}
		
		$this->loadContent();
		$this->dialog->setHidden(true);
	}
	
	public function onCancelAction(){
		$this->dialog->setHidden(true);
	}
	
	public function loadContent(){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();

		$target = intval($this->targetSelect->getSelectedValue());
		
		global $library;
		
		$cfg = require_once "$library/org.hailong.configs/classify.php";
		
		if(isset($cfg["targets"])){
			$this->targetSelect->setItems($cfg["targets"]);
		}
		else {
			$this->targetSelect->setItems(DBClassify::targets());
		}
		
		$pcid = trim($this->pcidText->getText());

		$task = new ClassifyQueryTask();
		
		$task->target = $target;
		
		if($pcid){
			$task->pcid = $pcid;
		}
		
		$context->handle("ClassifyQueryTask",$task);
		
		$items = array();
		
		if($task->results){
			foreach($task->results as $row){
				$item = array();
				$item["key"] = "<a href='javascript:;' action='pcid' key='{$row["cid"]}'>{$row["cid"]}</a>";
				$item["title"] = $row["title"];
				$item["logo"] = "<img width='32px' src='".RESURL($row["logo"],80)."' /><input type='button' value='设置' action='logo' key='{$row["cid"]}' />";
				$item["keyword"] = $row["keyword"];
				$item["command"] = "<input type='button' value='删除' action='remove' key='{$row["cid"]}'></input>"
					."<input type='button' value='修改' class='edit' key='{$row["cid"]}'></input>";
				$items[] = $item;
			}
		}

		$this->searchTable->setItems($items);
		
		$html = "";

		if($pcid){
			$task = new ClassifyParentTask();
			$task->cid = $pcid;
			$context->handle("ClassifyParentTask",$task);
			if($task->results){
				foreach($task->results as $item){
					$html = " / <a href='javascript:;' action='pcid' key='{$item->cid}'>{$item->title}</a>".$html;
				}
			}
		}
		
		$this->actionView->setHtml("<a href='javascript:;' action='pcid' key='0'>一级分类</a>".$html);
	}
	
	public function onTableAction(){
		
		$context = $this->getContext();
		
		$key = $this->searchTable->getActionKey();
		$action = $this->searchTable->getAction();
		$actionData = $this->searchTable->getActionData();
		
		if($action == "pcid"){
			$this->pcidText->setText($key);
			$this->loadContent();
		}
		else if($action == "add" || $action =="edit"){
			
			$pcid = trim($this->pcidText->getText());
			$keyword = isset($actionData["keyword"]) ? trim($actionData["keyword"]) : "";
			$title = isset($actionData["title"]) ? trim($actionData["title"]) : "";
			
			if(!$title){
				getCurrentViewContext()->pushFunction("window.alert","请输入标题");
				return ;
			}
			
			$task = false;
			
			$target = intval($this->targetSelect->getSelectedValue());
			
			if($action == "add"){
				$task = new ClassifyCreateTask();
				$task->pcid = $pcid ? $pcid : 0;
				$task->target = $target;
			}
			else{
				$task = new ClassifyUpdateTask();
				$task->cid = $key;
			}
			$task->keyword = isset($actionData["keyword"]) ? $actionData["keyword"] : "";
			$task->title = isset($actionData["title"]) ? $actionData["title"] : "";
			
			try{
				if($action == "add"){
					$context->handle("ClassifyCreateTask",$task);
				}
				else{
					$context->handle("ClassifyUpdateTask",$task);
				}
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
				return ;
			}
		}
		else if($action == "remove"){
		
			$task = new ClassifyRemoveTask();
			$task->cid = $key;
			try{
				$context->handle("ClassifyRemoveTask",$task);
				$this->loadContent();
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
				return ;
			}
		}
		else if($action == "logo"){
			
			$this->editForm->setFields(array("cid"=>$key));
			$this->dialog->setHidden(false);
		}

	}
	
	public function onSearchAction(){
		$this->searchPageListView->setSelectedValue("1");
		$this->loadContent();
	}
	
	public function onAction(){
		$this->pcidText->setText($this->actionView->getActionKey());
		$this->loadContent();
	}
	
	public function onTargetAction(){
		$this->loadContent();
	}
}

?>