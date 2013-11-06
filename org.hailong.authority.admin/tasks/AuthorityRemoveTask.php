<?php

/**
 * 权限删除任务
 * @author zhanghailong
 *
 */
class AuthorityRemoveTask extends AuthorityAdminTask{
	
	
	/**
	 * 目标ID
	 * @var int
	 */
	public $tid; 
	
	/**
	 * 目标类型
	 * @var DBAuthorityTargetType
	 */
	public $ttype;
	/**
	 * 角色ID
	 * @var int
	 */
	public $arid;
	/**
	 * 实体ID
	 * @var int
	 */
	public $aeid;

}

?>