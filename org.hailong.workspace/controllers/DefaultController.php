<?php

class DefaultController extends ViewController{
	
	private $logoutController;
	private $userInfoController;
	
	private $selectorView;
	private $webView;
	
	public function __construct($context,$isPostback=false){
		parent::__construct($context,$isPostback);

		$this->selectorView = new ListView("studio_selector_view");
		$this->webView = new WebView("studio_webview");
		
		$task = new AuthTask();
		
		try{
			$context->handle("AuthTask",$task);
			
			$this->logoutController = new LogoutController($context,$isPostback);
			$this->userInfoController = new UserInfoController($context,$isPostback);
			
		}
		catch(Exception $ex){
			global $library;
			$loginUrl = require("$library/org.hailong.configs/login_url.php");
			$loginUrl =  $loginUrl.urlencode("http://".$_SERVER["HTTP_HOST"].dirname($_SERVER["REQUEST_URI"]));
				
			getCurrentViewContext()->redirect($loginUrl);
	
			return;
		}
		
		if(!$isPostback){
			
			global $workspace;
			
			$modules = $workspace["modules"];
			
			$selectedValue = $this->selectorView->getSelectedValue();
			$selectedItem = null;
			$items = array();
			
			$context = $this->getContext();
			
			foreach($modules as $module){
				$item =  array("title"=>$module["title"],"href"=>$module["url"],"value"=>$module["uuid"]."://".$module["url"],"group"=>$module["group"]);
				if(isset($module["auth-alias"])){
					try{
						$task = new AuthorityEntityValidateTask($module["auth-alias"]);
						$context->handle("AuthorityEntityValidateTask",$task);
						
						$item["href"] = $module["url"];
					}
					catch(Exception $ex){
						if(isset($module["visable"]) && $module["visable"]){
							$item["href"] = $module["url-active"];
						}
						else {
							continue;
						}
					}
				}
				else{
					$item["href"] = $module["url"];
				}
				$items[] = $item;
				
				if($selectedValue == $item["value"]){
					$selectedItem = $item;
				}
			}
			
			$count = count($items);
			
			if(!$selectedItem && $count>0){
				$selectedItem = $items[0];
			}
			
			$this->selectorView->setItems( $items);
			$this->webView->setUrl($selectedItem["href"]);

			$this->selectorView->setSelectedValue($selectedItem["value"]);
			
			$this->selectorView->setSelectedChangeAction(new Action($this,"Selected"));
			
			$task = new StatisticsTask();
			$task->target = STATISTICS_WORKSPACE."/index.php";
			$task->key = "pv";
			$context->handle("StatisticsTask",$task);
		}
	}

	public function onLoadView(){
		
		
	}
	
	public function onAction(){
		
	}
	
	public function onLogout(){
		$this->logoutController->onLogout();
	}
	
	public function onSelected(){
		$selectedValue = $this->selectorView->getSelectedValue();
		$index = strpos($selectedValue, "://");
		
		if($index !== false){
			$this->webView->setUrl(substr($selectedValue, $index + 3));
		}
	}
	
}

?>