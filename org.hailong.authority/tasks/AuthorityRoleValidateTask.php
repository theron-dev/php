<?php

/**
 * 权限角色验证任务
 * @author zhanghailong
 *
 */
class AuthorityRoleValidateTask extends AuthTask{
	
	public $valid_role;
	public $uid;
	
	public function prefix(){
		return "authority";																							
	}
	
	public function __construct($role=AuthorityRoleAnonymous,$uid){
		$this->uid = $uid;
		$this->valid_role = $role;
	}
}

?>