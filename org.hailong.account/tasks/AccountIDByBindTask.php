<?php

/**
 * 获取账户ID任务
 * @author zhanghailong
 *
 */
class AccountIDByBindTask implements ITask{
	
	public $etype;
	public $eid;
	/**
	 * 输出 用户ID
	 * @var int
	 */
	public $uid;
	
	public function prefix(){
		return "acc";
	}
}

?>