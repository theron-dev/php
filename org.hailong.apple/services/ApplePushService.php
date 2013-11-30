<?php

/**
 * apple 推送服务
 * Tasks : ApplePushTask
 * @author zhanghailong
 *
 *				"config" => array(
					"host"=>"gateway.push.apple.com",
					"port"=>2195,
					"cert"=>"aps-vcheng.pem",
					"password"=>"wodoop",
				),
 */
class ApplePushService extends Service{
	
	private $apns;
	
	public function connect(){
		
		if($this->apns){
			fclose($this->apns);
			$apns = null;
		}
		
		$config  = $this->getConfig();
			
		$host  = isset($config["host"]) ? $config["host"]:'gateway.sandbox.push.apple.com';
		$port = isset($config["port"]) ? $config["port"]:2195;
		$cert = isset($config["cert"]) ? $config["cert"]:'aps-dev.pem';
		$password = isset($config["password"]) ? $config["password"]:null;
			
		$streamContext = stream_context_create();
			
		stream_context_set_option($streamContext, 'ssl', 'local_cert', $cert);
		
		if($password !== null){
			stream_context_set_option($streamContext, 'ssl', 'passphrase', $password);
		}
		
		$this->apns = stream_socket_client('ssl://' . $host . ':' . $port, $error, $errorString, 60,
		STREAM_CLIENT_CONNECT, $streamContext);
			
		if($error ){
			return new Exception($errorString,ERROR_APPLE_PUSH_ERROR);
		}
	}
	
	public function __destruct(){
		if($this->apns){
			fclose($this->apns);
			$apns = null;
		}
	}
	
	public function handle($taskType,$task){
		
		if($taskType == "ApplePushTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			if(!$this->apns){
				$this->connect();
			}
			
			$aps = array();
			
			if($task->alert !==null){
				$aps["alert"] = $task->alert;
			}
			
			if($task->badge !==null){
				$aps["badge"] = intval($task->badge);
			}
			
			if($task->sound !==null){
				$aps["sound"] = $task->sound;
			}
			
			$payload = array("aps"=>$aps);
			
			if($task->data !== null){
				$payload["data"] = $task->data;
			}
			
			$payload = json_encode($payload);
			
			$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', $task->token) . chr(0) .
				chr(strlen($payload)) .$payload;
			
			$reply = 3;
			
			while($this->apns === false 
				|| false === ($rs = fwrite($this->apns, $apnsMessage)) 
				|| $rs != strlen($apnsMessage)) {
				$this->connect();
				if( -- $reply ==0){
					throw new AppleException("apple push error".json_encode($this->getConfig()),ERROR_APPLE_PUSH_ERROR);
				}
			}
			
			return false;
		}
		
		return true;
	}
}

?>