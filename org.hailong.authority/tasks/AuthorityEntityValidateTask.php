<?php

/**
 * 权限实体验证任务
 * @author zhanghailong
 *
 */
class AuthorityEntityValidateTask extends AuthTask{
	
	public $valid_alias;
	public $uid;
	
	public function prefix(){
		return "authority";																							
	}
	
	public function __construct($alias,$uid=0){
		$this->uid = $uid;
		$this->valid_alias = $alias;
	}
}

?>