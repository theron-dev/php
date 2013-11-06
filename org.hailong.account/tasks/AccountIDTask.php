<?php

/**
 * 获取账户ID任务
 * @author zhanghailong
 *
 */
class AccountIDTask implements ITask{
	
	/**
	 * 账号
	 * @var String
	 */
	public $account;
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