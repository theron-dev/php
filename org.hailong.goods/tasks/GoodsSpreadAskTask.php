<?php

/**
* 物品推广索取任务
* @author zhanghailong
*
*/
class GoodsSpreadAskTask extends GoodsAuthTask{
	
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
	 * 输出 推广ID
	 * @var int
	 */
	public $sid;
	
	/**
	 * 输出 商品URL
	 * @var String
	 */
	public $url;
	/**
	 * 输出 商品WAP URL
	 * @var String
	 */
	public $wapUrl;

	/**
	* 输出 标题
	* @var String
	*/
	public $title;
	/**
	 * 输出 描述
	 * @var String
	 */
	public $body;
	/**
	 * 输出 图片
	 * @var String
	 */
	public $image;
	/**
	 * 输出 价格
	 * @var double
	 */
	public $price;
	/**
	* 单位
	* @var int
	*/
	public $unit;
	/**
	 * 输出 来源
	 * @var String
	 */
	public $source;
	
	/**
	* 输出 图片
	* @var array(url,url)
	*/
	public $images;
}

?>