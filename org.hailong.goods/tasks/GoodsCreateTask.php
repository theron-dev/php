<?php

/**
* 物品创建任务
* @author zhanghailong
*
*/
class GoodsCreateTask extends GoodsAuthTask{
	
	/**
	 * 用户ID　null　时使用内部参数 auth
	 * @var int
	 */
	public $uid;
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	/**
	 * 图片
	 * @var String
	 */
	public $image;
	/**
	 * 价格
	 * @var double
	 */
	public $price;
	/**
	 * 单位
	 * @var int
	 */
	public $unit;
	/**
	 * 来源
	 * @var String
	 */
	public $source;
	/**
	 * 外部物品URL
	 * @var String
	 */
	public $url;
	/**
	* 外部物品Wap URL
	* @var String
	*/
	public $wapUrl;
	/**
	 * 外部物品类型
	 * @var int
	 */
	public $etype;
	/**
	 * 外部物品ID
	 * @var int
	 */
	public $eid;
	/**
	* 推广ID
	* @var int
	*/
	public $sid;
	
	/**
	 * 图片
	 * @var array(url,url)
	 */
	public $images;
	
	/**
	 * 输出　
	 * @var DBGoods
	 */
	public $results;
	
	public function __construct(){
		$this->unit = DBGoodsUnitNone;
	}
}

?>