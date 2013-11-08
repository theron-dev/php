<?php

/**
* 匿名用户任务
* @author zhanghailong
*
*/
class CommentTask implements ITask{
	
	public function prefix(){
		return "comment";
	}
}

?>