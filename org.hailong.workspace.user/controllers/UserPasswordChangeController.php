<?php

class UserPasswordChangeController extends ViewController{
	
	private $form;
	private $passwordTextView;
	private $newpasswordTextView;
	private $renewpasswordTextView;
	private $messageLabel;
	
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);
		
		$this->passwordTextView = new TextView("password");
		$this->newpasswordTextView = new TextView("newpassword");
		$this->renewpasswordTextView = new TextView("renewpassword");
		$this->messageLabel = new Label("psmessage");
		$this->form = new Form("psform");
		
		if(!$isPostback){
			$this->passwordTextView->setText("");
			$this->newpasswordTextView->setText("");
			$this->renewpasswordTextView->setText("");
			$this->messageLabel->setText("");
			$this->form->setSubmitAction(new Action($this,"Submit"));
		}
	}
	
	public function checkInputData(){
		
		$password = $this->passwordTextView->getText();
		
		if(strlen($password) ==0){
			$this->messageLabel->setText("请输入原密码");
			return false;
		}
		
		$newpassword = $this->newpasswordTextView->getText();

		if(strlen($newpassword) ==0){
			$this->messageLabel->setText("请输入新密码");
			return false;
		}
		
		if($newpassword != $this->renewpasswordTextView->getText()){
			$this->messageLabel->setText("两次输入的密码不一至");
			return false;
		}
		
		return true;
	}
	
	public function onSubmit(){
		if($this->checkInputData()){
			
			$this->messageLabel->setText("");
			
			$context = $this->getContext();
			
			$task = new AccountPasswordChangeTask();
	
			try{
				
				$task->password = $this->passwordTextView->getText();;
				$task->newpassword = $this->newpasswordTextView->getText();
				
				$context->handle("AccountPasswordChangeTask",$task);
				
				getCurrentViewContext()->pushFunction("window.alert","成功修改密码");
				
				$this->passwordTextView->setText("");
				$this->newpasswordTextView->setText("");
				$this->renewpasswordTextView->setText("");
			}
			catch(Exception $ex){
				$this->messageLabel->setText($ex->getMessage());
				getCurrentViewContext()->pushFunction("window.alert",$ex->getMessage());
				return ;
			}
		}
	}
	
}

?>