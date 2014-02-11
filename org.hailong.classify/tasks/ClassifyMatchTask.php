<?php

/**
* 分类匹配任务
* @author zhanghailong
*
*/
class ClassifyMatchTask extends ClassifyTask{
	
	/**
	 * 匹配内容
	 * @var String
	 */
	public $body;
	/**
	 * 分类目标
	 * @var int
	 */
	public $target;
	/**
	 * 输出 
	 * @var array(cid1,cid2,...)
	 */
	public $results;
	
	public function __construct(){
		$this->target = DBClassifyTargetDefault;
	}
	
}

?>