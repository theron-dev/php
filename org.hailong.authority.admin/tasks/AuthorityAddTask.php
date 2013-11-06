<?php

/**
 * 权限创建任务
 * @author zhanghailong
 *
 */
class AuthorityAddTask extends AuthorityAdminTask{
	
	/**
	 * 输出参数 权限ID
	 * @var int
	 */
	public $aid;
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