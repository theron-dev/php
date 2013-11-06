<?php

class UserBaseInfoController extends ViewController{
	
	private $form;
	private $accountTextView;
	private $titleTextView;
	private $telTextView;
	private $emailTextView;
	private $messageLabel;
	
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->accountTextView = new TextView("account");
		$this->titleTextView = new TextView("title");
		$this->telTextView = new TextView("tel");
		$this->emailTextView = new TextView("email");
		$this->messageLabel = new Label("message");
		$this->form = new Form("form");
		
		if(!$isPostback){
			
			$this->messageLabel->setText("");
			$this->form->setSubmitAction(new Action($this,"Submit"));
			
			$task = new AccountByIDTask();
	
			try{
				$task->uid = $context->getInternalDataValue("auth");
				
				$context->handle("AccountByIDTask",$task);
				
				$this->accountTextView->setText($task->account);
				$this->titleTextView->setText($task->title);
				$this->telTextView->setText($task->tel);
				$this->emailTextView->setText($task->email);
				
			}
			catch(Exception $ex){
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
				return ;
			}
			
		}
	}
	
	public function checkInputData(){
		
		$title = trim($this->titleTextView->getText());
		
		if(strlen($title) ==0){
			$this->messageLabel->setText("请输入真实姓名");
			return false;
		}
		
		$email = trim($this->emailTextView->getText());

		if(strlen($email) ==0){
			$this->messageLabel->setText("请输入邮箱");
			return false;
		}
		
		return true;
	}
	
	public function onSubmit(){
		if($this->checkInputData()){
			$this->messageLabel->setText("");
			
		}
	}
	
}

?>