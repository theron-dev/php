<?php

/**
 * 日志任务
 * @author zhanghailong
 *
 */
class LogTask implements ITask{
	
	public $level;
	public $tag;
	public $source;
	public $body;
	
	public function prefix(){
		return "log";																							
	}
	
	public function __construct($level=LogLevelDebug,$tag="",$body="",$source=null){
		$this->level = $level;
		$this->tag = $tag;
		if($source){
			$this->source = $source;
		}
		else{
			$this->source = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"";
		}
		$this->body = $body;
	}
}

?>