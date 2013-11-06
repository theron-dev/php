<?php

/**
 * 权限日志任务
 * @author zhanghailong
 *
 */
class AuthorityLogTask extends LogTask{
	
	public function __construct($level=LogLevelDebug,$body="",$source=""){
		parent::__construct($level,"org.hailong.authority",$body,$source);
	}
}

?>