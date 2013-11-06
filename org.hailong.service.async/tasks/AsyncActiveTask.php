<?php

/**
 * 激活异步任务
 * @author zhanghailong
 *
 */
class AsyncActiveTask implements ITask{
	
	/**
	 * 配置文件
	 * @var String
	 */
	public $config;
	/**
	 * 任务类型
	 * @var String
	 */
	public $taskType;
	/**
	 * 任务类
	 * @var String
	 */
	public $taskClass;
	/**
	 * 任务数据
	 * @var array
	 */
	public $data;
	/**
	 * 队列名
	 * @var String
	 */
	public $rank;
	
	public function prefix(){
		return "async";
	}
	
	public function __construct(){
		$this->rank = "default";
	}
	
}

?>