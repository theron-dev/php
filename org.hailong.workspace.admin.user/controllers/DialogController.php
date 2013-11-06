<?php

class DialogController extends ViewController{
	
	private $pageSize = 30;

	private $dialog;
	
	private $userTable;
	
	private $userPageListView;
	
	private $roleTable;
	
	private $rolePageListView;
	
	private $entityTable;
	
	private $entityPageListView;

	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);

		$this->userTable = new TableView("dialog_user_table");
		
		$this->userPageListView = new ListView("dialog_user_page");
		
		$this->roleTable = new TableView("dialog_role_table");
		
		$this->rolePageListView = new ListView("dialog_role_page");
		
		$this->entityTable = new TableView("dialog_entity_table");
		
		$this->entityPageListView = new ListView("dialog_entity_page");
		
		$this->dialog = new Dialog("dialog");
		
		if(!$isPostback){
			$this->userTable->setClickAction(new Action($this,"DialogTableAction"));
			$this->roleTable->setClickAction(new Action($this,"DialogTableAction"));
			$this->entityTable->setClickAction(new Action($this,"DialogTableAction"));
		}
	}
	
	public function loadContent(){
		
		$context = $this->getContext();
		$dbContext = $context->dbContext();
		
		$dialog = $this->dialog->getDialog();
		$source = $this->dialog->getSource();
		$argument = $this->dialog->getArgument();
		
		$items = array();
		
		$this->userTable->setItems($items);
		$this->roleTable->setItems($items);
		$this->entityTable->setItems($items);
		
		if($dialog == "user"){
			
			$rowCount = $dbContext->countForEntity("DBAccount");
			
			$pageIndex = $this->userPageListView->getSelectedValue();
			if(!$pageIndex){
				$pageIndex = 1;
				$this->userPageListView->setSelectedValue("1");
			}
			
			$pageCount = $rowCount % $this->pageSize ? intval($rowCount / $this->pageSize) + 1 : intval($rowCount / $this->pageSize);
			
			$items = array();
			
			for($i=0;$i<$pageCount;$i++){
				$items[] = array("value"=>($i +1),"text"=>"第".($i +1)."页");
			}
			
			$this->userPageListView->setItems($items);
			
			$items = array();
			
			$offset = ($pageIndex -1) *  $this->pageSize;
			
			$rs = $dbContext->queryEntitys("DBAccount","1=1 ORDER BY createTime DESC LIMIT {$offset},{$this->pageSize}");
			
			if($rs){
					
				while($account = $dbContext->nextObject($rs,"DBAccount")){
			
					$item = array();
					$item["account"] = $account->account;
					$item["title"] = $account->title;
					$item["tel"] = $account->tel;
					$item["email"] = $account->email;
					
					$checked = false;
						
					if($source == "role"){
						$checked = $dbContext->countForEntity("DBAuthority","tid={$account->uid} and ttype=".DBAuthorityTargetTypeUser." and arid={$argument}") ;
					}
					else if($source == "entity"){
						$checked = $dbContext->countForEntity("DBAuthority","tid={$account->uid} and ttype=".DBAuthorityTargetTypeUser." and aeid={$argument}") ;
					}
						
					if($checked){
						$item["check"] = "<input type='checkbox' action='unchecked' key='{$account->uid}' checked='checked'></input>";
					}
					else{
						$item["check"] = "<input type='checkbox' action='checked' key='{$account->uid}'></input>";
					}

					$items[] = $item;
				}
				$dbContext->free($rs);
			}
			
			$this->userTable->setItems($items);
			
		}
		else if($dialog == "role"){
			
			$rowCount = $dbContext->countForEntity("DBAuthorityRole");
			
			$pageIndex = $this->rolePageListView->getSelectedValue();
			if(!$pageIndex){
				$pageIndex = 1;
				$this->rolePageListView->setSelectedValue("1");
			}
			
			$pageCount = $rowCount % $this->pageSize ? intval($rowCount / $this->pageSize) + 1 : intval($rowCount / $this->pageSize);
			
			$items = array();
			
			for($i=0;$i<$pageCount;$i++){
				$items[] = array("value"=>($i +1),"text"=>"第".($i +1)."页");
			}
			
			$this->rolePageListView->setItems($items);
			
			$items = array();
			
			$offset = ($pageIndex -1) *  $this->pageSize;
			
			$rs = $dbContext->queryEntitys("DBAuthorityRole","1=1 ORDER BY createTime DESC LIMIT {$offset},{$this->pageSize}");
			if($rs){
			
				while($role = $dbContext->nextObject($rs,"DBAuthorityRole")){
					$item = array();
					$item["name"] = $role->name;
					$item["title"] = $role->title;
					
					$checked = false;
					
					if($source == "user"){
						$checked = $dbContext->countForEntity("DBAuthority","tid={$argument} and ttype=".DBAuthorityTargetTypeUser." and arid={$role->arid}") ;
					}
					else if($source == "entity"){
						$checked = $dbContext->countForEntity("DBAuthority","tid={$role->arid} and ttype=".DBAuthorityTargetTypeRole." and aeid={$argument}") ;
					}
					
					if($checked){
						$item["check"] = "<input type='checkbox' action='unchecked' key='{$role->arid}' checked='checked'></input>";
					}
					else{
						$item["check"] = "<input type='checkbox' action='checked' key='{$role->arid}'></input>";
					}
							
					$items[] = $item;
				}
				$dbContext->free($rs);
			}
			
			$this->roleTable->setItems($items);
		}
		else if($dialog == "entity"){
			
			$rowCount = $dbContext->countForEntity("DBAuthorityEntity");
		
			$pageIndex = $this->entityPageListView->getSelectedValue();
			if(!$pageIndex){
				$pageIndex = 1;
				$this->entityPageListView->setSelectedValue("1");
			}
			
			$pageCount = $rowCount % $this->pageSize ? intval($rowCount / $this->pageSize) + 1 : intval($rowCount / $this->pageSize);
			
			$items = array();
			
			for($i=0;$i<$pageCount;$i++){
				$items[] = array("value"=>($i +1),"text"=>"第".($i +1)."页");
			}
			
			$this->entityPageListView->setItems($items);
			
			$items = array();
			
			$offset = ($pageIndex -1) *  $this->pageSize;
			
			$rs = $dbContext->queryEntitys("DBAuthorityEntity","1=1 ORDER BY createTime DESC LIMIT {$offset},{$this->pageSize}");
			
			if($rs){
			
				while($entity = $dbContext->nextObject($rs,"DBAuthorityEntity")){
					$item = array();
					$item["alias"] = $entity->alias;
					$item["title"] = $entity->title;
					
					$checked = false;
						
					if($source == "user"){
						$checked = $dbContext->countForEntity("DBAuthority","tid={$argument} and ttype=".DBAuthorityTargetTypeUser." and aeid={$entity->aeid}") ;
					}
					else if($source == "role"){
						$checked = $dbContext->countForEntity("DBAuthority","tid={$argument} and ttype=".DBAuthorityTargetTypeRole." and aeid={$entity->aeid}") ;
					}
						
					if($checked){
						$item["check"] = "<input type='checkbox' action='unchecked' key='{$entity->aeid}' checked='checked'></input>";
					}
					else{
						$item["check"] = "<input type='checkbox' action='checked' key='{$entity->aeid}'></input>";
					}
					
					$items[] = $item;
				}
				$dbContext->free($rs);
			}
			
			$this->entityTable->setItems($items);
		}
		
	}

	public function setDialog($dialog){
		$this->dialog->setDialog($dialog);
	}
	
	public function setSource($source){
		$this->dialog->setSource($source);
	}
	
	public function setArgument($argument){
		$this->dialog->setArgument($argument);
	}
	
	
	public function getTable(){
		$dialog = $this->dialog->getDialog();
		if($dialog == "user"){
			return $this->userTable;
		}
		if($dialog == "role"){
			return $this->roleTable;
		}
		if($dialog == "entity"){
			return $this->entityTable;
		}
	}
	
	public function onDialogTableAction(){
		$context = $this->getContext();
		$dialog = $this->dialog->getDialog();
		$source = $this->dialog->getSource();
		$argument = $this->dialog->getArgument();
		$table = $this->getTable();
		if($table){
			$action = $table->getAction();
			$key = $table->getActionKey();
			if($action == "ok"){
				$this->dialog->setDialog("");
			}
			else if($action =="unchecked"){
				
				try{
					$task = new AuthorityRemoveTask();
					if($dialog == "role"){
						if($source == "user"){
							$task->ttype = DBAuthorityTargetTypeUser;
							$task->tid = $argument;
							$task->arid = $key;
						}
						else if($source == "entity"){
							$task->aeid = $argument;
							$task->tid = $key;
							$task->ttype = DBAuthorityTargetTypeRole;
						}
					}
					else if($dialog == "entity"){
						$task->aeid = $key;
						if($source == "user"){
							$task->ttype = DBAuthorityTargetTypeUser;
							$task->tid = $argument;
						}
						else if($source == "role"){
							$task->ttype = DBAuthorityTargetTypeRole;
							$task->tid = $argument;
						}
					}
					else if($dialog == "user"){
						$task->ttype = DBAuthorityTargetTypeUser;
						$task->tid = $key;
						if($source == "role"){
							$task->arid = $argument;
						}
						else if($source == "entity"){
							$task->aeid = $argument;
						}
					}
					
					$context->handle("AuthorityRemoveTask",$task);
				}
				catch(Exception $ex){
					getCurrentViewContext()->pushFunction("window.alert",$ex.getMessage());
				}
				
				$this->loadContent();
			}
			else if($action == "checked"){
				
				try{
					$task = new AuthorityAddTask();
					if($dialog == "role"){
						if($source == "user"){
							$task->ttype = DBAuthorityTargetTypeUser;
							$task->tid = $argument;
							$task->arid = $key;
						}
						else if($source == "entity"){
							$task->aeid = $argument;
							$task->tid = $key;
							$task->ttype = DBAuthorityTargetTypeRole;
						}
					}
					else if($dialog == "entity"){
						$task->aeid = $key;
						if($source == "user"){
							$task->ttype = DBAuthorityTargetTypeUser;
							$task->tid = $argument;
						}
						else if($source == "role"){
							$task->ttype = DBAuthorityTargetTypeRole;
							$task->tid = $argument;
						}
					}
					else if($dialog == "user"){
						$task->ttype = DBAuthorityTargetTypeUser;
						$task->tid = $key;
						if($source == "role"){
							$task->arid = $argument;
						}
						else if($source == "entity"){
							$task->aeid = $argument;
						}
					}
					
					$context->handle("AuthorityAddTask",$task);
				}
				catch(Exception $ex){
					getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
				}
				
				$this->loadContent();
			}
		}
	}
	
	public function onDialogPageAction(){
		
	}
}

?>