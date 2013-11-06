<?php

/**
 * 日志清除任务
 * @author zhanghailong
 *
 */
class LogClearTask implements ITask{
	
	
	public function prefix(){
		return "log";																							
	}
	
}

?>