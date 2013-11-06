<?php

/**
 * 清理异步任务
 * @author zhanghailong
 *
 */
class AsyncCleanupTask implements ITask{
	
	public function prefix(){
		return "async";
	}

}

?>