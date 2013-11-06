<?php

/**
 * 获取绑定昵名的账户ID任务
 * @author zhanghailong
 *
 */
class AccountIDCheckNickTask implements ITask{
	
	/**
	 * 昵名
	 * @var String
	 */
	public $nick;
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