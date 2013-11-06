<?php

/**
 * 权限角色修改任务
 * @author zhanghailong
 *
 */
class AuthorityRoleUpdateTask extends AuthorityAdminTask{
	
	/**
	* 角色ID
	* @var String
	*/
	public $arid;
	
	/**
	 * 角色名
	 * @var String
	 */
	public $name;
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	/**
	* 说明
	* @var String
	*/
	public $description;

}

?>