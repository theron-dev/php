<?php

/**
* 父级分类任务
* @author zhanghailong
*
*/
class ClassifyParentTask extends ClassifyTask{
	
	/**
	* 分类ID
	* @var int
	*/
	public $cid;
	/**
	 * 输出 
	 * @var array(DBClassify,DBClassify,...)
	 */
	public $results;
	
	
}

?>