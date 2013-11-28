<?php

class ProductGetTask extends ProductTask{

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
	 * 输出	
	 * @var array(DBProduct)
	 */
	public $results;
}

?>