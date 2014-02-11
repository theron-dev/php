<?php

/**
* 物品查询任务
* @author zhanghailong
*
*/
class GoodsQueryTask extends GoodsTask{
	
	/**
	 * 类型
	 * @var String ,array(cid1,cid2,...),array(DBClassify,DBClassify,...), -1（未分类）
	 */
	public $cids;
	/**
	 * 关键词
	 * @var String
	 */
	public $keyword;
	/**
	* 分页
	* @var int
	*/
	public $pageIndex;
	public $pageSize;
	
	
	/**
	 * 输出　物品详情
	 * @var array(DBGoods,DBGoods, ...)
	 */
	public $results;
	/**
	 * 输出 总数
	 * @var int
	 */
	public $total;
	
}

?>