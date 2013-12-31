<?php

/**
 * 帐户信息服务
 * Tasks : AccountInfoUpdateTask AccountInfoAddTask AccountInfoGetTask
 * @author zhanghailong
 *
 */
class AccountInfoService extends Service{
	
	
	public function handle($taskType,$task){
		
		
		if($task instanceof AccountInfoUpdateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			
			$uid = $context->getInternalDataValue("auth");
			$item = $dbContext->get("DBAccountInfo",array("uiid"=>$task->uiid));
			
			if($item && $item->uid == $uid){
				$item->value =  $task->value;
				$item->text = $task->text;
				$item->updateTime = time();
				$dbContext->update($item);
			}

			return false;
		}
		
		if($task instanceof AccountInfoAddTask){

			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			
			$uid = $task->uid;
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$item = $dbContext->querySingleEntity("DBAccountInfo","`uid`={$uid} and `key`='{$task->key}'");

			if($item){
				$item->value = $task->value;
				$item->text = $task->text;
				$item->updateTime = time();
				$dbContext->update($item);
			}
			else{
				$item = new DBAccountInfo();
				$item->uid = $uid;
				$item->key = $task->key;
				$item->value =$task->value;
				$item->text = $task->text;
				$item->updateTime = time();
				$item->createTime = time();
				$dbContext->insert($item);
			}

			$task->uiid = $item->uiid;
			
			return false;
		}
		
		
		
		if($task instanceof AccountInfoGetTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$user = $dbContext->get("DBAccount",array("uid"=>$uid));
			
			if($user){
				
				$task->infos = array("title"=>$user->title,"account"=>$user->account);
				
				if($user->tel){
					$task->infos["tel"] = $user->tel;
				}
				
				if($user->weibo_uid){
					$task->infos["weibo_uid"] = $user->weibo_uid;
				}
				
				if($user->douban_uid){
					$task->infos["douban_uid"] = $user->douban_uid;
				}
				
				if($user->qq_uid){
					$task->infos["qq_uid"] = $user->qq_uid;
				}
				
				$sql = "uid={$uid}";
				
				if($task->keys){
					$sql .= " AND `key` IN ".$dbContext->parseArrayValue($task->keys);
				}
				
				$sql .=" ORDER BY uiid ASC";
				
				$rs = $dbContext->queryEntitys("DBAccountInfo",$sql);
				if($rs){
					
					while($item = $dbContext->nextObject($rs,"DBAccountInfo")){
						$task->infos[$item->key] = array("value"=>$item->value,"text"=>$item->text);
					}
					$dbContext->free($rs);
				}
				
			}
			else{
				throw new AccountException("user not found", ERROR_USER_NOT_FOUND);
			}

			return false;
		}
		
		if($task instanceof AccountInfoPutTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbTask = new DBContextTask();
			$dbTask->key = DB_ACCOUNT;
			
			$context->handle("DBContextTask", $dbTask);
			
			if($dbTask->dbContext){
				$dbContext = $dbTask->dbContext;
			}
			
			$uid = $task->uid;
				
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$user = $dbContext->get("DBAccount",array("uid"=>$uid));
			
			if($user){
				$info = $dbContext->querySingleEntity("DBAccountInfo","`uid`={$uid} and `key` ='{$task->key}' ");
				if($info){
					$info->value = $task->value;
					$info->text = $task->text;
					$info->updateTime = time();
					$dbContext->update($info);
				}
				else{
					$info = new DBAccountInfo();
					$info->uid = $uid;
					$info->key = $task->key;
					$info->value = $task->value;
					$info->text = $task->text;
					$info->updateTime = time();
					$info->createTime = time();
					$dbContext->insert($info);
				}
				
			}
			else{
				throw new AccountException("user not found", ERROR_USER_NOT_FOUND);
			}

			return false;
			
		}
		
		return true;
	}
}

?>