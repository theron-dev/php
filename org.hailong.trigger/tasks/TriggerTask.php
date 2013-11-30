<?php

class TriggerTask implements ITask{

	/**
	 * 触发器名 前导匹配
	 * @var String
	 */
	public $name;
	/**
	 * 数据
	 * @var any
	 */
	public $data;
	
	public function __construct($name=null,$data=null){
		$this->name = $name;
		$this->data = $data;
	}
	
	public function prefix(){
		return "trigger";
	}
}

?>