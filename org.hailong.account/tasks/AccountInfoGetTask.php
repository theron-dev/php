<?php

/**
 * 获取账户信息任务
 * @author zhanghailong
 *
 */
class AccountInfoGetTask extends AuthTask{
	
	/**
	 * 用户ID 若为 null 则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	/**
	 * Keys
	 * @var array("key1","key2")
	 */
	public $keys;
	/**
	 * 
	 * @var array("key"=>array("value"=>v,"text"=>t)) 
	 */
	public $infos;
	
	public function prefix(){
		return "acc";
	}
}

?>