<?php

/**
* 查询分类任务
* @author zhanghailong
*
*/
class ClassifyQueryTopTask extends ClassifyTask{
	
	/**
	* 父级分类ID
	* @var int
	*/
	public $pcid;
	/**
	 * 分类目标
	 * @var int
	 */
	public $target;
	
	/**
	 * 前TAG
	 * @var int
	 */
	public $top;
	/**
	 * 输出 
	 * @var array(array(),array(),...)
	 */
	public $results;
	
	public function __construct(){
		$this->pcid = 0;
		$this->target = DBClassifyTargetDefault;
		$this->top = 10;
	}

}

?>