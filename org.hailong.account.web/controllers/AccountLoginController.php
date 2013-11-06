<?php

class AccountLoginController extends ViewController{
	
	private $messageLabel;
	private $emailTextView;
	private $retTextView;
	private $passwordTextView;
	private $verifyCodeTextView;
	private $verifyCodeImage;
	private $verifyCodeView;
	private $form;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);

		$this->messageLabel = new Label("login_message");
		$this->emailTextView = new TextView("login_email");
		$this->retTextView = new TextView("login_ret");
		$this->passwordTextView = new TextView("login_password");
		$this->form = new Form("login_form");
		$this->verifyCodeImage = new Image("login_verify_img");
		$this->verifyCodeTextView = new TextView("login_verify_text");
		$this->verifyCodeView = new View("login_verify");
		
		if(!$isPostback){
			$this->form->setSubmitAction(new Action($this,"Login"));
			$this->messageLabel->setHidden(true);
			$this->passwordTextView->setText("");
			$this->verifyCodeView->setHidden(true);
			$this->verifyCodeTextView->setText("");
			
			$ret = $context->getInputDataValue("ret");
				
			if(!$ret){
				$this->retTextView->setText(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "index.php");
			}
			else{
				$this->retTextView->setText($ret);
			}
			
			setcookie("LOGIN-RETURN",$this->retTextView->getText());
		}
		
	}
	
	public function onLogin(){
		
		$context = $this->getContext();
		
		if(!$this->verifyCodeView->isHidden()){
			if($_SESSION["VerifyCode"] != $this->verifyCodeTextView->getText()){
				$this->messageLabel->setHidden(false);
				$this->messageLabel->setText("验证码错误");
				$this->passwordTextView->setText("");
				global $library;
				$url = require("$library/org.hailong.configs/verifyCode_url.php");
				$this->verifyCodeImage->setSrc($url.microtime());
				return;
			}
		}
		
		try{
			$task = new LoginTask();
			$task->account = $this->emailTextView->getText();
			$task->password = $this->passwordTextView->getText();
			$task->md5 = false;
			
			$context->handle("LoginTask",$task);
			
			getCurrentViewContext()->pushAttribute("window.location","href",$this->retTextView->getText());
		}
		catch(Exception $ex){
			$this->messageLabel->setHidden(false);
			$this->passwordTextView->setText("");
			
			$this->verifyCodeView->setHidden(false);
			
			global $library;
			$url = require("$library/org.hailong.configs/verifyCode_url.php");
			$this->verifyCodeImage->setSrc($url.microtime());
		}
		
	}

}

?>