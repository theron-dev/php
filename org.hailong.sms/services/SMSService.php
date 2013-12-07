<?php

/**
 *　SMS 服务
 * Tasks : SMSSendTask
 * @author zhanghailong
 */
class SMSService extends Service{

	private $emSMS;
	
	public function handle($taskType,$task){
	
		if($task instanceof SMSSendTask){
			
			if(class_exists("EMActivity")){
				if(!$this->emSMS){
					$this->emSMS = new EMActivity('emy','01');
				}
				if(is_array($task->tel)){
					foreach($task->tel as $tel){
						$res = $sms->sendmessage($tel,$task->body,101010);
						if(!$res){
							throw new SMSException($sms->errorinfo(),ERROR_SEND_SMS_ERROR);
						}
					}
				}
				else{
					$res = $sms->sendmessage($task->tel,$task->body,101010);
					if(!$res){
						throw new SMSException($sms->errorinfo(),ERROR_SEND_SMS_ERROR);
					}
				}
			}
			else{
				
				$context = $this->getContext();
				$cfg = $this->getConfig();
				
				if(isset($cfg["url"]) && isset($cfg["body"])){
					
					$url = $cfg["url"];
					$body = $cfg["body"];
					$method = isset($cfg["method"]) ? $cfg["method"] : "POST";
					$charset = isset($cfg["charset"]) ? $cfg["charset"] : "utf8";
					
					$query = array();
				
					foreach ($body as $key=>$value){
						
						if($value == "{body}"){
							$query[$key] = iconv("utf8", $charset, $task->body);
						}
						else if($value == "{tel}"){
							$query[$key] = $task->tel;
						}
						else{
							$query[$key] = $value;
						}
						
					}
					
					if($method != "POST"){
						if(strpos($url, "?") === false){
							$url .="?";
						}
						
						$url.=http_build_query($query);
					}
					
					$ch = curl_init($url);

					$context->setOutputDataValue("sms-url", $url);
					
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					
					if($method == "POST"){
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
					}

						
					$result = curl_exec($ch);
						
					curl_close($ch);
					
					$context->setOutputDataValue("sms-results", $result);
					
				}
				
			}	

			return false;
		}
		
		return true;
	}
}

?>