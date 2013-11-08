<?php

/**
* @创建用户任务
* @author zhanghailong
*
*/
class ATUserRemoveTask extends ATUserAuthTask{
	
	/**
	 * 用户ID 发起者 为null时使用内部参数auth
	 * @var int
	 */
	public $uid;
	/**
	 * 实体类型
	 * @var int
	 */
	public $etype;
	/**
	 * 实体ID
	 * @var int
	 */
	public $eid;
	/**
	* 目标类型
	* @var int
	*/
	public $ttype;
	/**
	 * 目标ID
	 * @var int
	 */
	public $tid;
	
}

?>