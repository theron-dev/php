<?php

/**
* 创建
* @author zhanghailong
*
*/
class PrizeCreateTask extends PrizeAuthTask{
	
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
	
	/**
	 * 周期
	 * @var int
	 */
	public $period;
}

?>