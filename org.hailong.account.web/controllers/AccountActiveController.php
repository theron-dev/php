<?php

class AccountActiveController extends ViewController{

	private $verifyTextView;
	private $verifyMessageLabel;
	
	private $form;

	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);

		$this->verifyTextView = new TextView("active_verify");
		$this->verifyMessageLabel = new Label("active_verify_message");
		
		$this->form = new Form("active_form");
		
		$task = new AuthTask();
		
		try{
			$context->handle("AuthTask",$task);
		}
		catch(Exception $ex){
			$verify = $context->getInputDataValue("verify");
			$url = "login.php?ret=active.php";
			if($verify){
				$url = "login.php?ret=".urlencode("active.php?verify={$verify}");
			}

			getCurrentViewContext()->pushAttribute("window.location","href",$url);
		}

		if(!$isPostback){
			$this->form->setSubmitAction(new Action($this,"Submit"));
			$verify = $context->getInputDataValue("verify");
			if(!$verify){
				$verify = "";
			}
			$this->verifyTextView->setText($verify);
			$this->verifyMessageLabel->setHidden(true);
		}
	}

	public function onSubmit(){
		
		$context  = $this->getContext();
		
		$this->verifyMessageLabel->setHidden(true);
		
		$task = new AccountEmailActiveTask();
		$task->verify = $this->verifyTextView->getText();
		
		try{
			$context->handle("AccountEmailActiveTask",$task);
			
			getCurrentViewContext()->pushAttribute("window.location","href","index.php");
		}
		catch(Exception $ex){
			if(ERROR_USER_LOGIN_TIMEOUT == $ex->getCode()){
				getCurrentViewContext()->pushAttribute("window.location","href","login.php?ret=active.php");
			}
			else{
				$this->verifyMessageLabel->setHidden(false);
				$this->verifyMessageLabel->setText("验证码错误");
			}
		}
	}

}

?>