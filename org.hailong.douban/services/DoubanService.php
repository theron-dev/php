<?php

class DoubanService extends Service{
	
	
	public function get($uri){
	
		$cfg = $this->getConfig();
			
		$url = "https://api.douban.com/v2";
		
		if(isset($cfg["url"])){
			$url = $cfg["url"];
		}
		
		$appkey = null;
		
		if(isset($cfg["appkey"])){
			$appkey = $cfg["appkey"];
		}
		
		if($appkey === null){
			global $library;
			$cfg = require "$library/org.hailong.configs/douban.php";
			if(isset($cfg["appkey"])){
				$appkey = $cfg["appkey"];
			}
		}
		
		$url .= $uri;
		
		if($appkey){
			if(strpos($url, "?") === false){
				$url .= "?appkey=".$appkey;
			}
			else{
				$url .= "&appkey=".$appkey;
			}
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		
		$result =  curl_exec($ch);
		
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		curl_close($ch);
		
		if($http_status != 200){
			throw new Exception($url,$http_status);
		}
		
		return json_decode($result,true);
	}
	
	public function handle($taskType, $task){
		
		if($task instanceof DoubanBookSearchTask){
			
			$uri = "/book/search?";
			
			$query = array();
			
			if($task->keyword){
				$query["q"] = $task->keyword;
			}
			else if($task->tag){
				$query["tag"] = $task->tag;
			}
			
			if($task->pageSize !== null){
				
				$pageSize = intval($task->pageSize);
				$pageIndex = intval($task->pageIndex);
				if($pageIndex < 1){
					$pageIndex = 1;
				}
				if($pageSize < 1){
					$pageSize = 10;
				}
				
				$query["start"] = ($pageIndex - 1) * $pageSize;
				$query["count"] = $pageSize;
				
			}
			
			$uri .= http_build_query($query);
			
			$task->results = $this->get($uri);
			
			return false;
		}
		
		if($task instanceof DoubanBookGetTask){
				
			$uri = "/book?";
					
			if($task->bookId){
				$uri = "/book/".$task->bookId;
			}
			else {
				$uri = "/book/isbn/".$task->isbn;
			}
			
			$task->results = $this->get($uri);
			
			return false;
		}
		
		
		return true;
	}
	
}
