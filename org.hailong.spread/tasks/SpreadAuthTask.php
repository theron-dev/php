<?php

/**
 * 推广验证任务
 * @author zhanghailong
 *
 */
class SpreadAuthTask extends AuthorityEntityValidateTask{
	
	public function prefix(){
		return "spread";
	}
	
	public function __construct(){
		parent::__construct("spread/admin");
	}
}

?>