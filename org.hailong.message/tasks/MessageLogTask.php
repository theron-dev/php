<?php

/**
 * 消息日志任务
 * @author zhanghailong
 *
 */
class MessageLogTask extends LogTask{
	
	public function __construct($level=LogLevelDebug,$body="",$source=""){
		parent::__construct($level,"org.hailong.message",$body,$source);
	}
}

?>