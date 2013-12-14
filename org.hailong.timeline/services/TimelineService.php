<?php

/**
 * 时间线服务
 * @author zhanghailong
 * @task TimelineTask TimelineCreateTask TimelineRemoveTask TimelineMaxEidTask TimelineLastCountTask TimelineQueryTask
 * @config array("dbKey"=>"数据标示","dbParts"=>"数据分区数","dbTable"=>"extends DBTimeline")
 */
class TimelineService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof TimelineTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			$config = $this->getConfig();	
			
			$dbContextTask = new DBContextTask($config["dbKey"]);

			if($task->uid === null){
				$task->uid = $context->getInternalDataValue("auth");
			}
			
			if(isset($config["dbParts"])){
				$dbContext->partKey = $task->uid % $config["dbParts"];
			}
			
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$task->dbContext = $dbContext;

		}
		
		if($task instanceof TimelineCreateTask){
			
			$context = $this->getContext();
			
			$config = $this->getConfig();
			$entityClass = $config["dbTable"];
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$item = new $entityClass();
			$item->etype = $task->etype;
			$item->eid = $task->eid;
			$item->uid = $uid;
			$item->timestamp = $task->timestamp;
			
			$task->dbContext->insert($item);
			
			$task->results = $item;
			
			return false;
		}
		
		if($task instanceof TimelineRemoveTask){
				
			$config = $this->getConfig();
			$entityClass = $config["dbTable"];
			
			if($task->tlid){
				$task->dbContext->delete($entityClass,"tlid=".intval($task->tlid));
			}
			else if($task->etype){
				
				$sql = "etype=".intval($task->etype);
				
				if($task->eid){
					$sql .= " AND eid=".intval($task->eid);
				}
				
				$task->dbContext->delete($entityClass,$sql);
			}
			
				
			return false;
		}
		
		if($task instanceof TimelineMaxTask){
		
			$config = $this->getConfig();
			$entityClass = $config["dbTable"];

			$uid = $task->uid;
				
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$task->results = $task->dbContext->querySingleEntity($entityClass,"uid={$uid} AND etype={$task->etype} ORDER BY tlid DESC LIMIT 1");
			
			return false;
		}
		
		if($task instanceof TimelineLastCountTask){
			
			$config = $this->getConfig();
			$entityClass = $config["dbTable"];
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$sql = "tlid>{$task->beginTlid} AND uid={$uid}";
			if($task->etypes){
				if(is_array($task->etypes)){
					$sql.= " AND etype IN ".$dbContext->parseArrayValue($task->etypes);
				}
				else{
					$sql.= " AND etype={$task->etypes}";
				}
			}
			$sql .= " ORDER BY tlid DESC";
			
			$task->results = $task->dbContext->countForEntity($entityClass,$sql);
				
			return false;
		}
		
		if($task instanceof TimelineQueryTask){
			
			$config = $this->getConfig();
			$entityClass = $config["dbTable"];
				
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$sql = "uid={$uid}";
			
			if($task->etypes){
				if(is_array($task->etypes)){
					$sql.= " AND etype IN ".$dbContext->parseArrayValue($task->etypes);
				}
				else{
					$sql.= " AND etype={$task->etypes}";
				}
			}
			if($task->minTlid !== null){
				$sql .=" AND ltid>{$task->minTlid}";
			}
			if($task->maxTlid !== null){
				$sql .=" AND ltid<{$task->maxTlid}";
			}
			
			$sql .= " ORDER BY tlid DESC LIMIT {$task->offset},{$task->limit}";
			
			$rs = $task->dbContext->queryEntitys($entityClass,$sql);
			
			if($rs){
				$task->results = array();
				while($item = $task->dbContext->nextObject($rs, $entityClass)){
					$task->results[] =$item;
				}
			}
			
			return false;
		}
		
		
		return true;
	}
}

?>