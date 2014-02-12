<?php

class AccountRegisterController extends ViewController{
	
	
	private $emailTextView;
	private $emailMessageLabel;
	
	private $passwordTextView;
	private $passwordMessageLabel;
	
	private $repasswordTextView;
	private $repasswordMessageLabel;
	
	private $form;

	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);

		$this->emailTextView = new TextView("reg_email");
		$this->emailMessageLabel = new Label("reg_email_message");
		
		$this->passwordTextView = new TextView("reg_password");
		$this->passwordMessageLabel = new Label("reg_password_message");
		
		$this->repasswordTextView = new TextView("reg_repassword");
		$this->repasswordMessageLabel = new Label("reg_repassword_message");
		
		$this->form = new Form("reg_form");
		
		if(!$isPostback){
			$this->form->setSubmitAction(new Action($this,"Submit"));
			$this->emailTextView->setChangeAction(new Action($this,"Change"));
			$this->passwordTextView->setText("");
			$this->repasswordTextView->setText("");
			$this->emailMessageLabel->setHidden(true);
			$this->passwordMessageLabel->setHidden(true);
			$this->repasswordMessageLabel->setHidden(true);
		}

	}
	
	public function onChange(){
		
		$context  = $this->getContext();
		
		$email = $this->emailTextView->getText();
		
		$this->emailMessageLabel->setHidden(true);
		$this->passwordMessageLabel->setHidden(true);
		$this->repasswordMessageLabel->setHidden(true);
		
		if(!$email || strlen($email) ==0){
			$this->emailMessageLabel->setHidden(true);
		}
		else{
			try{
				$task = new AccountEmailValidateTask();
				$task->email = $email;
				
				$context->handle("AccountEmailValidateTask",$task);
				
				$this->emailMessageLabel->setHidden(true);
			}
			catch(Exception $ex){
				$this->emailMessageLabel->setHidden(false);
				if($ex->getCode() == ERROR_USER_EMAIL_FORMAT_ERROR){
					$this->emailMessageLabel->setText("请输入正确的 email");
				}
				else{
					$this->emailMessageLabel->setText("已存在");
				}
			}
		}
	}
	
	public function onSubmit(){
		
		$context  = $this->getContext();
		
		$this->emailMessageLabel->setHidden(true);
		$this->passwordMessageLabel->setHidden(true);
		$this->repasswordMessageLabel->setHidden(true);
		
		$email = $this->emailTextView->getText();
		$password =$this->passwordTextView->getText();
		$repassword = $this->repasswordTextView->getText();
		
		
		if($password != $repassword){
			$this->repasswordMessageLabel->setHidden(false);
			$this->repasswordMessageLabel->setText("两次密码不一至");
		}
		else{
			
			try{
				$task = new AccountEmailRegisterTask();
				$task->email = $email;
				$task->password =$password;
				
				$context->handle("AccountEmailRegisterTask",$task);
				
				$this->emailTextView->setText("");
				
				$task = new LoginTask();
				$task->account = $email;
				$task->password = $password;
				$task->md5 = false;
					
				$context->handle("LoginTask",$task);

				getCurrentViewContext()->redirect("active.php");
			}
			catch(Exception $ex){
				$this->emailMessageLabel->setHidden(false);
				if($ex->getCode() == ERROR_USER_EMAIL_FORMAT_ERROR){
					$this->emailMessageLabel->setText("请输入正确的 email");
				}
				else{
					$this->emailMessageLabel->setText("已存在");
				}
			}
		}
	}
	

}

?>