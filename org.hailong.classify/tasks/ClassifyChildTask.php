<?php

/**
* 子级分类任务
* @author zhanghailong
*
*/
class ClassifyChildTask extends ClassifyTask{
	
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