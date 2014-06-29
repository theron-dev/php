<?php

define("APP_AUTH_TOKEN","%^&0ef448dDIOF");

/**
 * 应用凭证服务
 * @author zhanghailong
 * @Tasks AppAuthTask , AppAuthRemoveTask
 */
class AppAuthService extends Service{

	
	public function handle($taskType,$task){
		
		if($task instanceof AppAuthTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_APP;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$config = $this->getConfig();
			
			$anonymous = isset($config["anonymous"]) ? $config["anonymous"] : false;
			$timeout = isset($config["timeout"]) ? $config["timeout"] : 30 * 24 * 3600;
	
			if($task->appid == null && isset($config["appid"])){
				$task->appid = $config["appid"];
			}
			
			$app = $dbContext->querySingleEntity("DBApp","appid={$task->appid}");
			
			if(!$app){
				throw new AppException("not found app ".$task->appid,ERROR_APP_NOT_FOUND);
			}
			
			if($task->account && $task->password){
				
				$loginTask = new LoginTask();
				$loginTask->account = $task->account;
				$loginTask->password = $task->password;
				$loginTask->md5 = $task->md5;
				
				$context->handle("LoginTask", $loginTask);
				
				$auth = $context->getInternalDataValue("auth");
				$did = $context->getInternalDataValue("device-did");
					
				if($auth && $did){
					$appAuth = $dbContext->querySingleEntity("DBAppAuth","appid={$task->appid} and uid=$auth and did=$did");
					if($appAuth){
						$appAuth->secret = AppAuthService::genAuthSecret($app->secret);
						$appAuth->sign = client_sign();
						$appAuth->token = AppAuthService::genAuthToken($task->appid,$appAuth->secret,$appAuth->sign,$auth,$did);
						if($task->setting){
							$appAuth->setting = $task->setting;
						}
						$appAuth->updateTime = time();
						$dbContext->update($appAuth);
					}
					else{
						$appAuth = new DBAppAuth();
						$appAuth->appid = $task->appid;
						$appAuth->uid = $auth;
						$appAuth->did = $did;
						if($task->setting){
							$appAuth->setting = $task->setting;
						}
						$appAuth->secret = AppAuthService::genAuthSecret($app->secret);
						$appAuth->sign = client_sign();
						$appAuth->token = AppAuthService::genAuthToken($task->appid,$appAuth->secret,$appAuth->sign,$auth,$did);
						$appAuth->updateTime = time();
						$appAuth->createTime = time();
						$dbContext->insert($appAuth);
					}
					
					$context->setInternalDataValue("auth-token", $appAuth->token);
					$context->setOutputDataValue("auth", $auth);
					$context->setOutputDataValue("auth-token", $appAuth->token);
					$context->setOutputDataValue("auth-setting", $appAuth->setting);
					
					$_SESSION["auth"] = $auth;
					
					setcookie("auth-token",$appAuth->token);
				}
				else{
					if(!$anonymous){
						throw new AppException("not handle Login task", ERROR_APP_NOT_HANDLE_LOGIN_TASK);
					}
				}
			}
			else{
				
				$auth = $task->uid;
				
				if($auth === null){
					$auth =  $context->getInternalDataValue("auth");
				}
				
				if($auth === null && isset($_SESSION["auth"])){
					$auth = $_SESSION["auth"];
				}
				
				$did = $task->did;

				if($did === null){
					$did = $context->getInternalDataValue("device-did");
				}
				
				if($auth && $did){
					$appAuth = $dbContext->querySingleEntity("DBAppAuth","appid={$task->appid} and uid={$task->uid} and did=$did");
						
					$token = $task->token;
					
					if($token == null && isset($_COOKIE["auth-token"])){
						$token = $_COOKIE["auth-token"];
					}
					
					if($appAuth){
						if($appAuth->token != $token 
							|| $appAuth->secret != AppAuthService::genAuthSecret($app->secret)){
							if(!$anonymous){
								throw new AppException("auth token error", ERROR_APP_AUTH_TOKEN);
							}
						}
						else{
							
							if($appAuth->sign != client_sign() 
									&& (time() - $appAuth->updateTime) > $timeout){
								if(!$anonymous){
									throw new AppException("auth token error", ERROR_APP_AUTH_TOKEN);
								}
							}
							else{
								
								if($appAuth->sign != client_sign() 
									|| ($task->setting && $task->setting != $appAuth->setting)){
									$appAuth->sign = client_sign();
									if($task->setting){
										$appAuth->setting = $task->setting;
									}
									$appAuth->updateTime = time();
									$dbContext->update($appAuth);
								}
								
								if($appAuth->setting){
									$context->setOutputDataValue("auth-setting", $appAuth->setting);
								}
								
								$context->setInternalDataValue("auth", $appAuth->uid);
								$context->setInternalDataValue("auth-token", $token);
								$context->setOutputDataValue("auth", $appAuth->uid);
							}
							
							
						}
						
					}
					else{
						if(!$anonymous){
							throw new AppException("auth token error", ERROR_APP_AUTH_TOKEN);
						}
					}
				}
				else{
					if(!$anonymous){
						throw new AppException("auth token error", ERROR_APP_AUTH_TOKEN);
					}
				}
			}
			
		}
		
		if($task instanceof AppAutoAuthTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbTask = new DBContextTask();
			$dbTask->key = DB_APP;
		
			$context->handle("DBContextTask", $dbTask);
		
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$config = $this->getConfig();
				
			$anonymous = isset($config["anonymous"]) ? $config["anonymous"] : false;
			
			$auth = $context->getInternalDataValue("auth");

			$did = $task->did;
			
			if($did === null){
				$did = $context->getInternalDataValue("device-did");
			}
			
			$appid = $task->appid;
			
			if($appid === null){
				$appid = isset($config["appid"]) ? $config["appid"] : null;
			}
			
			if($auth && $did && $appid){
	
				$app = $dbContext->querySingleEntity("DBApp","appid={$appid}");
				
				if(!$app){
					throw new AppException("not found app ".$task->appid,ERROR_APP_NOT_FOUND);
				}
			
				$appAuth = $dbContext->querySingleEntity("DBAppAuth","appid={$appid} and uid={$auth} and did={$did}");
				
				if($appAuth){
					$appAuth->secret = AppAuthService::genAuthSecret($app->secret);
					$appAuth->sign = client_sign();
					$appAuth->token = AppAuthService::genAuthToken($task->appid,$appAuth->secret,$appAuth->sign,$auth,$task->did);
					$appAuth->updateTime = time();
					$dbContext->update($appAuth);
				}
				else{
					$appAuth = new DBAppAuth();
					$appAuth->appid = $appid;
					$appAuth->uid = $auth;
					$appAuth->did = $did;
					$appAuth->secret = AppAuthService::genAuthSecret($app->secret);
					$appAuth->sign = client_sign();
					$appAuth->token = AppAuthService::genAuthToken($task->appid,$appAuth->secret,$appAuth->sign,$auth,$task->did);
					$appAuth->updateTime = time();
					$appAuth->createTime = time();
					$dbContext->insert($appAuth);
				}
				
				$context->setInternalDataValue("auth-token", $appAuth->token);
				$context->setOutputDataValue("auth", $auth);
				$context->setOutputDataValue("auth-token", $appAuth->token);
				$context->setOutputDataValue("auth-setting", $appAuth->setting);
				
				$_SESSION["auth"] = $auth;
				
				setcookie("auth",$auth);
				setcookie("auth-token",$appAuth->token);

			}
			else{
				if(!$anonymous){
					throw new AppException("auth token error", ERROR_APP_AUTH_TOKEN);
				}
			}
		}
		
		if($task instanceof AppAuthRemoveTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_APP;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$config = $this->getConfig();
			
			$anonymous = isset($config["anonymous"]) ? $config["anonymous"] : false;
			$timeout = isset($config["timeout"]) ? $config["timeout"] : 30 * 24 * 3600;
	
			if($task->appid == null && isset($config["appid"])){
				$task->appid = $config["appid"];
			}
			
			$app = $dbContext->querySingleEntity("DBApp","appid={$task->appid}");
			
			if(!$app){
				throw new AppException("not found app ".$task->appid,ERROR_APP_NOT_FOUND);
			}
			
			$auth = $context->getInternalDataValue("auth");
			$did = $context->getInternalDataValue("device-did");
			
			if($auth && $did){
				$dbContext->delete("DBAppAuth","appid={$task->appid} and uid={$auth} and did={$did}");
				$dbContext->delete("DBAppDevice","appid={$task->appid} and and did={$did}");
			}
		}
		
		return true;
	}
	
	public static function genAuthToken($appid,$secret,$sign,$uid,$did){
		return md5(APP_AUTH_TOKEN.$appid."-".$secret."-".$sign."-".$uid."-".$did."-".time().APP_AUTH_TOKEN);
	}
	
	public static function genAuthSecret($token){
		return md5(APP_AUTH_TOKEN.$token.APP_AUTH_TOKEN);
	}
}

?>