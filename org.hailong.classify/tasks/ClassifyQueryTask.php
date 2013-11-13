<?php

/**
* 查询分类任务
* @author zhanghailong
*
*/
class ClassifyQueryTask extends ClassifyTask{
	
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
	 * 输出 
	 * @var array(array(),array(),...)
	 */
	public $results;
	
	public function __construct(){
		$this->pcid = 0;
		$this->target = DBClassifyTargetDefault;
	}

}

?>