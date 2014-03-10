<?php

/**
 *　email 服务
 * Tasks : eMailSendTask
 * config: {"smtp":"smtp.exmail.qq.com","port":25,"user":"msg@hailong.org",password:"",from:"msg@hailong.org"}
 * @author zhanghailong
 */
class eMailService extends Service{
	
	public static function result($resp){
		//echo $resp;
		$index = strpos($resp, " ");
		if($index !== false){
			return array("code"=>substr($resp, 0,$index),"status"=>substr($resp, $index));
		}
		else{
			return array("code"=>0,"status"=>"undefined error");
		}
	}
	
	public static function fputs($sock,$str){
		//echo $str;
		fputs($sock, $str);
	}
	
	public function handle($taskType,$task){
	
		if($task instanceof eMailSendTask){
			
			global $uploadDir;
			$from = $task->from;
			
			if(!$from){
				$from = "";
			}
			
			$config = $this->getConfig();
			
			if(!isset($config[$from])){
				throw new eMailException("not found smtp config from:{$from}",ERROR_EMAIL_NOT_FOUND_SMTP_CONFIG);
			}
			
			$config = $config[$from];
			
			$smtp  = isset($config["smtp"]) ? $config["smtp"] : null;
			$port = isset($config["port"])  ? $config["port"] : 25;
			$user = isset($config["user"]) ? $config["user"] : null;
			$password = isset($config["password"]) ? $config["password"] : null;
			
			if(!$from && isset($config["from"]) ){
				$from = $config["from"];
			}
			
			if(class_exists("Mail")){
				
				$headers = array ('From' => $from,
				   'To' => $task->to,
				   'Subject' => $task->title);
				
				$mail = Mail::factory('smtp',
					array ('host' => $smtp,
				     'port' => $port,
				     'auth' => true,
				     'username' => $user,
				     'password' => $password));
				
				$rs = $mail->send($to, $headers, $task->body);
				
				if($rs instanceof Exception){
					throw new eMailException($rs->getCode(), $rs->getMessage());
				}
				
				return false;
			}
			
			
			$sock = fsockopen($smtp,$port);
			
			if(!$sock){
				throw new eMailException("socket fsockopen error", ERROR_EMAIL_SOCKET);
			}
			
			$rs = eMailService::result(fgets($sock,512));
				
			if($rs["code"]!=220){
				throw new eMailException($rs["status"], $rs["code"]);
			}
			
			eMailService::fputs($sock, "HELO localhost\r\n");
			
			$rs = eMailService::result(fgets($sock,512));
				
			if($rs["code"]!=250){
				throw new eMailException($rs["status"], $rs["code"]);
			}
			
			if($user){
				eMailService::fputs($sock, "AUTH LOGIN ".base64_encode($user)."\r\n");
				
				$rs = eMailService::result(fgets($sock,512));
			
				if($rs["code"]!=334){
					throw new eMailException($rs["status"], $rs["code"]);
				}
				
				eMailService::fputs($sock,base64_encode($password)."\r\n");
							
				$rs = eMailService::result(fgets($sock,512));
			
				if($rs["code"]!=235){
					throw new eMailException($rs["status"], $rs["code"]);
				}
			}
			
			eMailService::fputs($sock,"MAIL FROM:<{$from}>\r\n");
			
			$rs = eMailService::result(fgets($sock,512));
			if($rs["code"]!=250){
				throw new eMailException($rs["status"], $rs["code"]);
			}
			
			eMailService::fputs($sock,"RCPT TO:<{$task->to}>\r\n");
			
			$rs = eMailService::result(fgets($sock,512));
			if($rs["code"]!=250){
				throw new eMailException($rs["status"], $rs["code"]);
			}
			
			eMailService::fputs($sock,"DATA\r\n");
			
			$rs = eMailService::result(fgets($sock,512));
			if($rs["code"]!=354){
				throw new eMailException($rs["status"], $rs["code"]);
			}
			
			$title = str_replace("\n","",str_replace("\r", "", $task->title));
			
			$data = "";
			
			$data .= "MIME-Version: 1.0\r\n";
			$data .= "To: {$task->to}\r\n";
			$data .= "From: {$from}\r\n";
			$data .= "Subject: ".$title."\r\n";
			$data .= "Date: ".date("r")."\r\n";
			
			$boundary = md5(date('r', time()));
			
			$data .= "Content-Type: multipart/mixed; boundary=\"".$boundary."\"\r\n";
			
			$data .= "\r\n";
			
			eMailService::fputs($sock,$data);
			
			$data = "--".$boundary."\r\n";
			
			if($task->contentType){
				$data .= "Content-Type: {$task->contentType}\r\n";
			}
			else{
				$data .= "Content-Type: text/plan; charset=UTF-8;\r\n";
			}
			
			$data .="\r\n";
			
			eMailService::fputs($sock, $data);
			
			eMailService::fputs($sock, $task->body);
			
			eMailService::fputs($sock,"\r\n");
			
			if($task->attachs && count($task->attachs) >0){
				foreach($task->attachs as $attach){
					$file = $uploadDir ? $uploadDir.$attach["uri"] : $attach["uri"];
					
					$data = "\r\n--".$boundary."\r\n";
					$data .= "Content-Type: {$attach["type"]}; name=\"{$attach["key"]}\";\r\n";
					$data .= "Content-Disposition: attachment; filename=\"".basename($attach["uri"])."\"; size=".filesize($file).";\r\n";
					$data .= "Content-Transfer-Encoding: base64;\r\n";

					$data .=" \r\n";

					eMailService::fputs($sock,$data);
					
					eMailService::fputs($sock,chunk_split(base64_encode(file_get_contents($file))));
					
					eMailService::fputs($sock,"\r\n");
				}
			}
			
			eMailService::fputs($sock,"\r\n--{$boundary}--\r\n");
			
			eMailService::fputs($sock, "\r\n.\r\n");
			
			$rs = eMailService::result(fgets($sock,512));
			if($rs["code"]!=250){
				throw new eMailException($rs["status"], $rs["code"]);
			}
			
			eMailService::fputs($sock,"QUIT\r\n");
			
			$rs = eMailService::result(fgets($sock,512));
			if($rs["code"]!=221){
				throw new eMailException($rs["status"], $rs["code"]);
			}
			
			fclose($sock);
			
			return false;
		}
		
		return true;
	}
}

?>