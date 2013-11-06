<?php

/**
 * 重置异步任务
 * @author zhanghailong
 *
 */
class AsyncResetTask implements ITask{
	
	/**
	 * 异步任务ID
	 * @var String
	 */
	public $atid;
	
	
	public function prefix(){
		return "async";
	}
	
	public function __construct(){
		
	}
	
}

?>