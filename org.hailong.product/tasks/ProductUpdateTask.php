<?php

class ProductUpdateTask extends ProductAuthTask{

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
	* 标价
	* @var double
	*/
	public $price;
	/**
	 * 出售价格
	 * @var double
	 */
	public $salePrice;
	/**
	* 目标
	* @var String
	*/
	public $target;
}

?>