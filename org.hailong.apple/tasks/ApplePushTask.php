<?php

/**
 * apple 推送
 * @author zhanghailong
 *
 */
class ApplePushTask implements ITask{
	
	public $token;
	public $alert;
	public $badge;
	public $sound;
	public $data;
	
	public function prefix(){
		return "applepush";																							
	}
}

?>