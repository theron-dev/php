<?php

/**
 *　消息发送任务
 * Tasks : MessageSendTask
 * @author zhanghailong
 *
 */
class MessageSendService extends Service{
	
	public function handle($taskType,$task){
	
		if($task instanceof MessageSendTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_MESSAGE);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			if(!$task->uid){
				$task->uid = $context->getInternalDataValue("auth");
			}
			
			if($task->targets && is_array($task->targets)){
				$tids = array();
				$uids = array();
				$muids = array();
				$users = array();
				foreach($task->targets as $target){
					if(is_array($target)){
						if(isset($target["tid"])){
							$tids[$target["tid"]] = $target;
						}
						else if(isset($target["uid"])){
							$uids[$target["uid"]] = $target;
						}
						else if(isset($target["muid"])){
							$muids[$target["muid"]] = $target;
						}
						else if(isset($target["nick"])){
							$t = new AccountIDCheckNickTask();
							$t->nick = $target["nick"];
							$context->handle("AccountIDCheckNickTask", $t);
							if($t->uid){
								$target["uid"] = $t->uid;
								$uids[$t->uid] = $target;
							}
						}
						else if(isset($target["key"]) && isset($target["type"])){
							$users[] = $target;
						}
					}
				}
				
				$t = array();
				foreach($muids as $muid => $target){
					$user = $dbContext->get("DBMessageUser",array("muid"=>$muid));
					if($user){
						if($user->uid){
							$uids[$user->uid] = $target;
						}
						else{
							$t[$muid] = $target;
						}
					}
					else if(isset($target["key"]) && isset($target["type"])){
						$users[] = $target;
					}
				}
				$muids = $t;
				
				foreach($users as $target){
					$accessTask = new MessageUserAccessTask();
					$accessTask->key = $target["key"];
					$accessTask->type = $target["type"];
					
					$context->handle("MessageUserAccessTask",$accessTask);
					
					if(!$accessTask->exists){
						
						$invokeTask = new MessageUserInvokeTask();
						$invokeTask->key = $target["key"];
						$invokeTask->type = $target["type"];
						$context->handle("MessageUserInvokeTask", $invokeTask);
						
						$ftitle = isset($target["title"]) ? $target["title"]: $target["key"];
							
						$replyMsg = new MessageSendTask();
						$replyMsg->uid = -1;
						$replyMsg->body = str_replace("{ftitle}", $ftitle, MESSAGE_INVOKE_SYS_MSG_FORMAT);
						$replyMsg->targets = array(array("uid"=>$task->uid));
							
						$context->handle("MessageSendTask",$replyMsg);
					}
					
					if($accessTask->uid){
						$uids[$accessTask->uid] = $target;
					}
					if($accessTask->muid){
						$muids[$accessTask->muid] = $target;
					}
				}
				
				if(count($muids) + count($uids) + count($tids) >1){
					
					$invokes = array();
					
					foreach ($uids as $uid=>$target){
						$relation = new UserRelationTask();
						$relation->uid = $task->uid;
						$relation->fuid = $uid;
						$relation->source = "message";
						$context->handle("UserRelationTask",$relation);
					}
					
					foreach($tids as $tid){
						$members = $dbContext->queryEntitys("DBMeetingMember","tid={$tid}");
						if($members){
							while($member = $dbContext->nextObject($members,"DBMeetingMember")){
								if($member->uid){
									$uids[$member->uid] = $member;
								}
								else if($member->muid){
									$muids[$member->muid] = $member;
								}

							}
							$dbContext->free($members);
						}
					}

					$meeting = new DBMeeting();
					$meeting->uid = $task->uid;
					$meeting->updateTime = time();
					$meeting->createTime = time();
					
					$dbContext->insert($meeting);
					
					$member = new DBMeetingMember();
					$member->black = 0;
					$member->updateTime = time();
					$member->createTime = time();
					$member->tid = $meeting->tid;
					$member->uid = $task->uid;
					$dbContext->insert($member);
					
					foreach($muids as $muid=>$targer){
						$member = new DBMeetingMember();
						$member->black = 0;
						$member->updateTime = time();
						$member->createTime = time();
						$member->tid = $meeting->tid;
						$member->muid = $muid;
						$dbContext->insert($member);
					}
					
					foreach($uids as $uid=>$target){
						$member = new DBMeetingMember();
						$member->black = 0;
						$member->updateTime = time();
						$member->createTime = time();
						$member->tid = $meeting->tid;
						$member->uid = $uid;
						$dbContext->insert($member);
					}
					
					$task->results = MessageSendService::sendMeetingMessage($context, $dbContext, $task, $meeting->tid);
				}
				else if(count($uids) ==1){
					$task->results = MessageSendService::sendMessageToAccount($context, $dbContext, $task, $uids);
				}
				else if(count($muids) ==1){
					$task->results = MessageSendService::sendMessageToUser($context, $dbContext, $task, $muids);
				}
				else if(count($tids) ==1){
					$tid = 0;
					foreach($tids as $key=>$value){
						$tid = $key;
						break;
					}
					$meeting = $dbContext->get("DBMeeting",array("tid"=>$tid));
					if($meeting){
						$task->results = MessageSendService::sendMeetingMessage($context, $dbContext, $task, $tid);
					}
					else{
						throw new MessageException("not found meeting {$tid}",ERROR_MESSAGE_NOT_FOUND_MEETING);
					}
				}
				else{
					throw new MessageException("targets error".json_encode($task),ERROR_MESSAGE_SEND_TARGETS);
				}
			}
			else{
				throw new MessageException("targets error",ERROR_MESSAGE_SEND_TARGETS);
			}
			
			return false;
		}
		
		return true;
	}
	

	
	private static function sendMessageToUser($context,$dbContext,$task,$muids){
		$msg = new DBMessage();
		$msg->uid = $task->uid;
		$msg->mtype = $task->mtype;
		$msg->mstate = MessageStateNone;
		foreach($muids as $muid=>$target){
			$msg->tmuid = $muid;
			break;
		}
		$msg->title = $task->title;
		$msg->body = $task->body;
		$msg->hasAttach = $task->attachs && is_array($task->attachs) && count($task->attachs) >0? 1:0;
		$msg->updateTime = time();
		$msg->createTime = time();
		$dbContext->insert($msg);
			
		if($msg->hasAttach){
			foreach($task->attachs as $attach){
				if(is_array($attach) && isset($attach["key"]) && isset($attach["uri"]) && isset($attach["type"])){
					$msgAttach = new DBMessageAttach();
					$msgAttach->key = $attach["key"];
					$msgAttach->uri = $attach["uri"];
					$msgAttach->contentType = $attach["type"];
					$msgAttach->mid = $msg->mid;
					$msgAttach->createTime = time();
					$dbContext->insert($msgAttach);
				}
			}
		}
		
		return $msg;
	}
	
	private static function sendMessageToAccount($context,$dbContext,$task,$uids){
		$msg = new DBMessage();
		$msg->uid = $task->uid;
		$msg->mtype = $task->mtype;
		$msg->mstate = MessageStateNone;
		foreach($uids as $uid=>$target){
			$msg->tuid = $uid;
			break;
		}
		$msg->title = $task->title;
		$msg->body = $task->body;
		$msg->hasAttach = $task->attachs && is_array($task->attachs) && count($task->attachs) >0 ? 1:0;
		$msg->updateTime = time();
		$msg->createTime = time();
		$dbContext->insert($msg);
			
		if($msg->hasAttach){
			foreach($task->attachs as $attach){
				if(is_array($attach) && isset($attach["key"]) && isset($attach["uri"]) && isset($attach["type"])){
					$msgAttach = new DBMessageAttach();
					$msgAttach->key = $attach["key"];
					$msgAttach->uri = $attach["uri"];
					$msgAttach->contentType = $attach["type"];
					$msgAttach->mid = $msg->mid;
					$msgAttach->createTime = time();
					$dbContext->insert($msgAttach);
				}
			}
		}
		
		if($task->uid >0){
			$relation = new UserRelationTask();
			$relation->uid = $task->uid;
			$relation->fuid = $uid;
			$relation->source = "message";
			$context->handle("UserRelationTask",$relation);
		}
		
		$remind = new MessageRemindTask();
		$remind->mid = $msg->mid;
		$remind->uid = $uid;
		$context->handle("MessageRemindTask",$remind);

		return $msg;
	}
	
	private static function sendMeetingMessage($context,$dbContext,$task,$tid){
		$msg = new DBMessage();
		$msg->uid = $task->uid;
		$msg->mtype = $task->mtype;
		$msg->mstate = MessageStateNone;
		$msg->tid = $tid;
		$msg->title = $task->title;
		$msg->body = $task->body;
		$msg->hasAttach = $task->attachs && is_array($task->attachs) && count($task->attachs) >0? 1:0;
		$msg->updateTime = time();
		$msg->createTime = time();
		$dbContext->insert($msg);
			
		if($msg->hasAttach){
			foreach($task->attachs as $attach){
				if(is_array($attach) && isset($attach["key"]) && isset($attach["uri"]) && isset($attach["type"])){
					$msgAttach = new DBMessageAttach();
					$msgAttach->key = $attach["key"];
					$msgAttach->uri = $attach["uri"];
					$msgAttach->contentType = $attach["type"];
					$msgAttach->mid = $msg->mid;
					$msgAttach->createTime = time();
					$dbContext->insert($msgAttach);
				}
			}
		}
			
		$members = $dbContext->queryEntitys("DBMeetingMember","tid={$tid} and black<>1");
		if($members){
			while($member = $dbContext->nextObject($members,"DBMeetingMember")){
				if($member->uid && $member->uid != $task->uid){
					$remind = new MessageRemindTask();
					$remind->mid = $msg->mid;
					$remind->uid = $member->uid;
					$context->handle("MessageRemindTask",$remind);
				}
			}
			$dbContext->free($members);
		}
		
		return $msg;
	}
}

?>