<?php

/**
 * 解除手机绑定任务
 * @author zhanghailong
 *
 */
class AccountTelUnBindTask extends AuthTask{
	
	/**
	 * 用户ID， 若不存在，则使用内部参数 auth
	 * @var int
	 */
	public $uid;

}

?>