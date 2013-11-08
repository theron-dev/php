<?php

/**
 * 分类同步外观
 * @author zhanghailong
 *
 */
class ClassifySyncFace extends SyncEntityFace{
	
	private $_dbContext;
	
	public function __construct($name,$etype,$dependentFaces=array()){
		parent::__construct($name, $etype,$dependentFaces,0, 0);

	}
	
	protected function dbContext($context){
		if($this->_dbContext == null){
	
			$this->_dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_CLASSIFY);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$this->_dbContext = $dbContextTask->dbContext;
			}
		}
		
		return $this->_dbContext;
	}
	
	
	/**
	 * 得到修改是时间在 timestamp 以后的实体
	 * @param int $timestamp
	 * @return ResultSet
	 */
	public function queryEntitysForTimestamp($context,$appid,$uid,$did,$timestamp){
		$dbContext = $this->dbContext($context);
		return $dbContext->queryEntitys("DBClassify","pcid=0 and updateTime>={$timestamp} ORDER BY updateTime ASC");
	}
	
	/**
	 * 得到实体ID对应的实体
	 * @param ISerivceContext $context
	 * @param long $eid
	 */
	public function getEntity($context,$eid){
		$dbContext = $this->dbContext($context);
		return $dbContext->get("DBClassify",array("cid"=>$eid));
	}
	
	public function nextEntity($context,$rs){
		$dbContext = $this->dbContext($context);
		return $dbContext->nextObject($rs,"DBClassify");
	}
	
	public function free($context,$rs){
		$dbContext = $this->dbContext($context);
		$dbContext->free($rs);
	}
	
	/**
	 * 实体ID
	 * @param DBEntity $item
	 * @return long
	 */
	public function eid($item){
		return $item->cid;
	}
	
	public function commit($context){
		$dbContext = $this->dbContext($context);
		$dbContext->query("commit");
	}
}

?>