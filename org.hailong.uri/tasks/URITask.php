<?php

/**
* URI任务
* @author zhanghailong
*
*/
class URITask implements ITask{
	
	/**
	 * 全URL
	 * @var String
	 */
	public $url;
	
	/**
	 * 输出 短URI
	 * @var String
	 */
	public $uri;
	
	public function prefix(){
		return "uri";
	}
}

?>