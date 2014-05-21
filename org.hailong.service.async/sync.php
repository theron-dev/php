<?php

global $library;

$library = dirname(__FILE__)."/..";

header("Content-Type: text/html; charset=utf-8");

require_once "$library/org.hailong.service.async/async.php";

$rank = "default";

if($argc > 1){
	$rank = $argv[1];
}

global $async_lock;
global $sync_php;

$lock = $async_lock.".".$rank.".async";

echo $lock."\n<br />";

if(!file_exists($lock)){
	
	set_time_limit(0);
	
	$lockFile = fopen($lock, "w");

	fwrite($lockFile, "running...");
	
	fflush($lockFile);
		
	echo "async DBContext create ...\n<br />";
	
	$dbConfig = require "$library/org.hailong.configs/db_default.php";
	
	$dbAdpater = newDBAdapter($dbConfig["type"],$dbConfig["servername"],$dbConfig["database"],$dbConfig["username"],$dbConfig["password"]);
	
	$dbAdpater->connect();
	
	$dbAdpater->setCharset($dbConfig["charset"]);
	
	$dbContext = new DBContext($dbAdpater);

	date_default_timezone_set("PRC");

	echo "async DBContext create OK\n<br />";
	
	echo "\n";
	
	$dbContext->delete("DBAsyncTask",time()." - createTime > ".(7*24*3600)." and (state=".AsyncTaskStateOK." or state=".AsyncTaskStateError.")");
	
	while($async = $dbContext->querySingleEntity("DBAsyncTask","state=".AsyncTaskStateNone." ORDER BY atid ASC")){
		$async->state = AsyncTaskStateRunning;
		$dbContext->update($async);
		
		try{
			
			echo "Task type:{$async->taskType} class:{$async->taskClass} running ...\n<br />";
			
			$data = json_decode($async->data,true);
			
			if(!$data){
				$data = array();
			}
			
			require_once "{$library}/org.hailong.configs/{$async->config}.php";
			
			$config =array();
			
			if(function_exists($async->config)){
				$func = $async->config;
				$config = $func();
			}
			
			$context = new ServiceContext($data,$config);
		
			$context->setDbContext(getDefaultDBContext());
			
			if(class_exists($async->taskClass)){
				$taskClass = $async->taskClass;
				$task = new $taskClass();
				$context->fillTask($task);
				
				try{
					$context->handle($async->taskType, $task);
					$async->state = AsyncTaskStateOK;
					echo "Task type:{$async->taskType} class:{$async->taskClass} OK\n<br />";
					$async->results = json_encode($context->getOutputData());
				}
				catch(Exception $ex){
					var_dump($ex);
					$async->state = AsyncTaskStateError;
					echo "Task type:{$async->taskType} class:{$async->taskClass} Error: {$ex->getMessage()}\n<br />";
					$async->results = $ex->getCode() .": ".$ex->getMessage();
				}
			}
			else{
				$async->state = AsyncTaskStateError;
				echo "Task type:{$async->taskType} class:{$async->taskClass} Error\n<br />";
				$async->results = "not found class ".$async->taskClass;
			}
		}
		catch(Exception $ex){
			$async->state = AsyncTaskStateError;
			$async->results = $ex->getCode() .": ".$ex->getMessage();
		}
		
		$dbContext->update($async);
	}

	fclose($lockFile);
	
	if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
		system("del ".str_replace("/", "\\", $lock));
	}
	else{
		unlink($lock);
	}
	
	echo "async Finish\n<br />";
}


?>