<?php

/**
 * 权限实体修改任务
 * @author zhanghailong
 *
 */
class AuthorityEntityUpdateTask extends AuthorityAdminTask{
	
	/**
	* 实体ID
	* @var int
	*/
	public $aeid;
	/**
	 * 别名
	 * @var String
	 */
	public $alias; 
	/**
	 * 标题
	 * @var String
	 */
	public $title;

}

?>