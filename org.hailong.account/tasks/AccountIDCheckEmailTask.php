<?php

/**
 * 获取绑定邮箱的账户ID任务
 * @author zhanghailong
 *
 */
class AccountIDCheckEmailTask implements ITask{
	
	/**
	 * 邮箱
	 * @var String
	 */
	public $email;
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