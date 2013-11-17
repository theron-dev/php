<?php

class ProductPublishTask extends ProductAuthTask{

	/**
	*　商品ID
	* @var int
	*/
	public $pid;
	/**
	 * 可售数量， -1为不限制
	 * @var int
	 */
	public $count;
	/**
	 * 出售时间
	 * @var int
	 */
	public $saleTime;
	/**
	 * 结束时间 0 为不限制
	 * @var int
	 */
	public $endTime;
	/**
	* 标价
	* @var double
	*/
	public $price;
	/**
	 * 出售价格
	 * @var double
	 */
	public $salePrice;
}

?>