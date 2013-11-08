<?php

/**
* 匿名用户任务
* @author zhanghailong
*
*/
class LikedTask implements ITask{
	
	public function prefix(){
		return "liked";
	}
}

?>