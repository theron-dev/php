<?php

class DBContextTask implements ITask{
	
	/**
	 * 数据上下文标示
	 * @var String
	 */
	public $key;
	/**
	 * 分区标示　与 数据上下文标示结合： key/partKey 获取上下文件实例，　为null或不存在实例则使用主分区数据上下文
	 * @var String
	 */
	public $partKey;
	/**
	 * 输出 数据上下文
	 * @var DBContext
	 */
	public $dbContext;
	
	public function prefix(){
		return "db";
	}
	
	public function __construct($key=null){
		$this->key = $key;
	}
}

?>