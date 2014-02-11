<?php

/**
* 物品导入任务
* @author zhanghailong
*
*/
class GoodsImportTask extends GoodsAuthTask{
	
	/**
	* 用户ID　null　时使用内部参数 auth
	* @var int
	*/
	public $uid;
	/**
	 * 物品URL
	 * @var String
	 */
	public $url;
	
	/**
	* 外部物品类型
	* @var String
	*/
	public $etype;
	/**
	 * 外部物品ID
	 * @var int
	 */
	public $eid;
	
	/**
	 * 统计码
	 * @var String
	 */
	public $outerCode;
	
	/**
	 * 输出　物品详情
	 * @var DBGoods
	 */
	public $results;
	
}

?>