<?php

/**
 * 
 * @author zhanghailong
 *
 */
class BooksCreateTask extends BooksAuthTask{
	
	/**
	 * 支付金额
	 * @var double
	 */
	public $payMoney;
	
	/**
	 * 实际支出
	 * @var double
	 */
	public $expendMoney;
	
	/**
	 * 单位
	 * @var DBBooksMoneyUnit
	 */
	public $unit;
	
	/**
	 * 类型
	 * @var DBBooksType
	 */
	public $type;
	/**
	 * 纬度
	 * @var latitude
	 */
	public $latitude;
	
	/**
	 * 经度
	 * @var longitude
	 */
	public $longitude;
	
	/**
	 * 其他信息 json
	 * @var String
	 */
	public $body;
	
	/**
	 * 输出
	 * @var DBBooks
	 */
	public $results;
	
	public function __construct(){
		$this->unit = DBBooksMoneyUnitRMB;
		$this->type = DBBooksTypeExpend;
	}
}

?>