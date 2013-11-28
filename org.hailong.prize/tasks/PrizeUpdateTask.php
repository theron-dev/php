<?php

/**
* 修改 
* @author zhanghailong
*
*/
class PrizeUpdateTask extends PrizeAuthTask{
	
	/**
	 * 奖品ID
	 * @var int
	 */
	public $pid;
	
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	
	/**
	 * 内容
	 * @var String
	 */
	public $body;
	
	/**
	 * 规则
	 * @var String
	 */
	public $rule;
	
	/**
	 * 图片
	 * @var [{uri:"",width:"",height:""}]
	 */
	public $images;
}

?>