<?php

/**
* 关系验证
* @author hailongz
*
*/
class ConcernCheckTask extends ConcernAuthTask{

	/**
	 *  用户ID　若为 null 则使用内部参数 auth
	 * @var int
	 */
	public $uid;
	
	/**
	 * 目标用户ID, 必填
	 * @var int
	 */
	public $tuid;

}

?>