<?php

/**
* URI访问任务
* @author zhanghailong
*
*/
class URIAssignTask implements ITask{
	
	/**
	 * 短URI
	 * @var String
	 */
	public $uri;
	
	/**
	* 输出 全URL
	* @var String
	*/
	public $url;
	
	public function prefix(){
		return "uri";
	}
}

?>