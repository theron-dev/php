<?php

/**
 * 
 * @author zhanghailong
 *
 */
class LBSSourceUpdateTask extends LBSTask{
	
	/**
	 * 来源类型
	 * @var DBLBSSourceType
	 */
	public $stype;
	/**
	 * 来源ID
	 * @var int
	 */
	public $sid;
	/**
	 * 纬度
	 * @var latitude
	 */
	public $latitude;
	/**
	 * 经度
	 * @var longitude
	 */
	public $longitude;
	
}

?>