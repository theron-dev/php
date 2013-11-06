<?php

/**
 * 获取绑定手机号的账户ID任务
 * @author zhanghailong
 *
 */
class AccountIDCheckTelTask implements ITask{
	
	/**
	 * 手机号
	 * @var String
	 */
	public $tel;
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