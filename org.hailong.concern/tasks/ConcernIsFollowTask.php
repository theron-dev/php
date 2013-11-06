<?php

/**
* 是否关注用户任务
* @author hailongz
*
*/
class ConcernIsFollowTask extends ConcernTask{

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
	
	/**
	 * 输出 
	 * @var boolean
	 */
	public $results;

}

?>