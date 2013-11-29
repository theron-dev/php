<?php

class ProductCreateTask extends ProductAuthTask{
	
	/**
	 * 实体类型
	 * @var int
	 */
	public $etype;
	/**
	 * 实体ID
	 * @var int
	 */
	public $eid;
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
	
	/**
	 * 输出
	 * @var DBProduct
	 */
	public $results;
}

?>