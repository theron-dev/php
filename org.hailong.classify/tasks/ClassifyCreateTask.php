<?php

/**
* 创建分类任务
* @author zhanghailong
*
*/
class ClassifyCreateTask extends ClassifyTask{
	
	/**
	 * 分类目标
	 * @var int
	 */
	public $target;
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	/**
	 * 图标
	 * @var String
	 */
	public $logo;
	/**
	 * 关键词
	 * @var String
	 */
	public $keyword;
	/**
	 * 父级分类ID
	 * @var int
	 */
	public $pcid;
	/**
	 * 输出 分类ID
	 * @var int
	 */
	public $cid;
	
	public function __construct(){
		$this->pcid = 0;
		$this->target = DBClassifyTargetDefault;
	}
	
}

?>