<?php

/**
* 匿名用户任务
* @author zhanghailong
*
*/
class PublishTask implements ITask{
	
	public function prefix(){
		return "publish";
	}
}

?>