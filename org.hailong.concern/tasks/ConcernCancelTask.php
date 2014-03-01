<?php

/**
* 取消关系
* @author hailongz
*
*/
class ConcernCancelTask extends ConcernAuthTask{

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
	public $changed;
	
	public function prefix(){
		return "concern";
	}
}

?>