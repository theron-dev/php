<?php

/**
 *　信息流服务
 * Tasks : CacheGetTask CachePutTask CacheRemoveTask
 * @author zhanghailong
 *
 */
class CacheService extends Service{
	
	private $cache;
	private $memcache;
	
	public function __construct(){
		$this->cache = array();
	}
	
	public function getMemcache(){
		if($this->memcache === null){
			if(class_exists("Memcache")){
				$config  = $this->getConfig();
				$host = isset($config["host"]) ? $config["host"] : "localhost";
				$port = isset($config["port"]) ? $config["port"] : 11211;
				$this->memcache = new Memcache();
				$this->memcache->connect($host,$port) or die("Memcache not cnnect");
			}
			else{
				$this->memcache = false;
			}
		}
		return $this->memcache;
	}
	
	public function handle($taskType,$task){
	
		if($task instanceof CacheGetTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_CACHE);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$path = $task->path;
			
			$config  = $this->getConfig();
			$timeout = isset($config["timeout"]) ? $config["timeout"] : 120;
			
			if(isset($this->cache[$path])){
				$item = $this->cache[$path];
				$task->value = $item && $item->value !== null ? json_decode($item->value,true) : null;
				$task->timestamp = $item ? $item->updateTime : null;
				$task->cid = $item ? $item->cid : null;
			}
			else{
				
				$item = null;
				$memcache = $this->getMemcache();
				
				if($memcache){
					$item = $memcache->get($path);
				}
				
				if($item){
					$task->value = $item->value ? json_decode($item->value,true) : null;
					$task->timestamp = $item->updateTime;
					$task->cid = $item ? $item->cid : null;
				}
				else{
					$item = $dbContext->querySingleEntity("DBCache","path='{$path}'");
					$this->cache[$path] = $item;
					$task->value = $item && $item->value !== null ? json_decode($item->value,true) : null;
					$task->timestamp = $item ? $item->updateTime : null;
					$task->cid = $item ? $item->cid : null;
					if($memcache){
						$memcache->set($path,$item);
					}
				}
			}

			return false;
		}
		
		if($task instanceof CachePutTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_CACHE);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$config  = $this->getConfig();
			$timeout = isset($config["timeout"]) ? $config["timeout"] : 120;
			$expire  = $task->expire ? intval($task->expire) : 3600;
			
			$path = $task->path;

			$item = null;
			$memcache = $this->getMemcache();
			
			if($memcache){
				$item = $memcache->get($path);
			}
			
			if($item){
				$item->value = json_encode($task->value);
				if((time() - $item->updateTime) > $timeout){
					$item->updateTime = time();
					$dbContext->update($item);
				}
			}
			else{
				$item = $dbContext->querySingleEntity("DBCache","path='{$path}'");
					
				if($item){
					$item->value = json_encode($task->value);
					if(!$memcache || (time() - $item->updateTime) > $timeout){
						$item->updateTime = time();
						$item->expire = $expire;
						$dbContext->update($item);
					}
				}
				else{
					$item = new DBCache();
					$item->value = 	json_encode($task->value);
					$item->path = $path;
					$item->expire = $expire;
					$item->updateTime = time();
					$item->createTime = time();
					$dbContext->insert($item);
				}
			}
			
			if($memcache){
				$memcache->set($path,$item,null,$expire);
			}
			
			$this->cahce[$path] = $item;
			
			$task->timestamp = $item->updateTime;
			$task->cid = $item->cid;
				
			return false;
		}
		
		if($task instanceof CacheRemoveTask){
		
			$context = $this->getContext();
			$dbContext = $context->dbContext();
		
			$dbContextTask = new DBContextTask(DB_CACHE);
		
			$context->handle("DBContextTask",$dbContextTask);
		
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$dbContext->delete("DBCache",array("cid"=>$task->cid));
		
			return false;
		}
		
		if($task instanceof CacheCleanupTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext(DB_CACHE);
			
			$dbContext->delete("DBCache",time()." - updateTime > expire OR isnull(expire)");
			
			return false;
		}
		
		return true;
	}
}

?>