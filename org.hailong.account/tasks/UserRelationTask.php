<?php

/**
 * 用户关系任务
 * @author zhanghailong
 *
 */
class UserRelationTask implements ITask{
	
	/**
	 * 主动 用户ID
	 * @var int
	 */
	public $uid;
	/**
	 * 被动 用户ID
	 * @var int
	 */
	public $fuid;
	/**
	 * 关系来源
	 * @var String
	 */
	public $source;
	
	public function prefix(){
		return "user-relation";
	}
	
}

?>