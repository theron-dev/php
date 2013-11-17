<?php

class ProductGetForUpdateTask extends ProductAuthTask{

	/**
	*　商品ID
	* @var int
	*/
	public $pid;
	
	/**
	 * 输出	
	 * @var DBProduct
	 */
	public $results;
}

?>