<?php

/**
 * 
 * @author zhanghailong
 *
 */
class LBSSearchTask extends LBSTask{
	

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
	
	/**
	 * 限制距离 米
	 * @var int
	 */
	public $distance;
	
	public $pageIndex;
	public $pageSize;
	
	/**
	 * 输出	
	 * @var array(DBLBSSearch,)
	 */
	public $results;
	
	public function onUpateSource($context,$dbContext){
		
		$sid = intval($this->sid);
		$stype = intval($this->stype);
		$latitude = doubleval($this->latitude);
		$longitude = doubleval($this->longitude);
		$distance = intval($this->distance);
		
		if(!$distance){
			$distance = 1000;
		}
		
		$dr = $distance / 111100.0;
			
		$sql = "SELECT * FROM ".DBLBSSource::tableName()." WHERE sid!={$sid} AND stype={$stype}"
			." AND latitude>=".($latitude - $dr)
			." AND latitude<=".($latitude + $dr)
			." AND longitude>=".($longitude - $dr)
			." AND longitude<=".($longitude + $dr);
			
		$rs = $dbContext->query($sql);
			
		if($rs){
		
			$t = new LBSDistanceTask();
		
			while($row = $dbContext->nextObject($rs,"DBLBSSource")){
					
				$r = new DBLBSSearch();
				$r->stype = $stype;
				$r->sid = $sid;
				$r->updateTime = $row->updateTime;
				$r->createTime = time();
				$r->near_sid = $row->sid;
				$r->near_stype = $row->stype;
				$r->near_latitude = $row->latitude;
				$r->near_longitude = $row->longitude;
					
					
				$t->latitude1 = $latitude;
				$t->longitude1 = $longitude;
				$t->latitude2 = $row->latitude;
				$t->longitude2 = $row->longitude;
					
				$context->handle("LBSDistanceTask",$t);
					
				$r->distance = $t->distance;
					
				$dbContext->insert($r);
			}
		
			$dbContext->free($rs);
		}
	
	}
}

?>