<?php

/**
* 终端设备验证任务　记录终端设备信息　生成did　以便做数据差异同步
* @author zhanghailong
*
*/

class DeviceAuthTask implements ITask{
	
	public $did;
	public $unique;
	public $type;
	public $name;
	public $systemName;
	public $systemVersion;
	public $model;

	public function prefix(){
		return "device";
	}
}

?>