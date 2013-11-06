<?php

class AccountForgotController extends ViewController{
	
	
	private $emailTextView;
	private $emailMessageLabel;
	
	private $form;

	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);

		$this->emailTextView = new TextView("reg_email");
		$this->emailMessageLabel = new Label("reg_email_message");
		
		$this->form = new Form("reg_form");
		
		if(!$isPostback){
			$this->form->setSubmitAction(new Action($this,"Submit"));
			$this->emailMessageLabel->setHidden(true);
		}

	}
	
	public function onSubmit(){
		
		$context  = $this->getContext();
		
		$this->emailMessageLabel->setHidden(true);

		
		$email = $this->emailTextView->getText();

		$task = new AccountResetPasswordTask();
		$task->account = $email;
		
		try{
			
			$context->handle("AccountResetPasswordTask",$task);
			
			getCurrentViewContext()->pushFunction("window.alert","成功重置密码， 请查收你的注册邮箱.");
		}
		catch(Exception $ex){
			getCurrentViewContext()->pushFunction("window.alert","此账号不存在");
		}
		
	}
	

}

?>