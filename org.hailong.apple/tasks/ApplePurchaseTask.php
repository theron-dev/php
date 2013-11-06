<?php

/**
 * apple 支付
 * @author zhanghailong
 *
 */
class ApplePurchaseTask implements ITask{
	
	/**
	 * 用户ID null 时使用内部参数  auth
	 * @var int
	 */
	public $uid;
	
	/**
	 * 商品
	 * @var String
	 */
	public $product;
	/**
	 * 事务
	 * @var String
	 */
	public $transaction;
	/**
	 * 验证数据
	 * @var String
	 */
	public $receipt;
	
	/**
	 * 输出 
	 * @var DBApplePurchase
	 */
	public $results;
	
	/**
	 * 输出 原状态
	 * @var DBApplePurchaseState
	 */
	public $state;
	
	public function prefix(){
		return "apple";																							
	}
}

?>