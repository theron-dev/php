<?php

/**
 * 建立关系
 * @author hailongz
 *
 */
class ConcernCreateTask extends ConcernAuthTask{

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
	 * 关系来源
	 * @var String
	 */
	public $source;
	
	/**
	 * 输出　关系ID
	 * @var int
	 */
	public $cid;
	
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