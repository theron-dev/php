<?php

/**
* 修改分类任务
* @author zhanghailong
*
*/
class ClassifyUpdateTask extends ClassifyTask{
	/**
	 * 标题 , null 时不修改
	 * @var String
	 */
	public $title;
	/**
	 * 图标 , null 时不修改
	 * @var String
	 */
	public $logo;
	/**
	* 关键词  null 时不修改
	* @var String
	*/
	public $keyword;
	/**
	 * 父级分类ID , null 时不修改
	 * @var int
	 */
	public $pcid;
	/**
	 * 分类ID
	 * @var int
	 */
	public $cid;

}

?>