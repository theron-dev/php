<?php


class DBContext{

	private $dbAdapter;
	private $lastSql;

	public function DBContext($dbAdapter = null){
		if($dbAdapter == null){
			$this->dbAdapter = getDefaultDBAdapter();
		}
		else{
			$this->dbAdapter = $dbAdapter;
		}
	}
	
	private function createEntity($entityClass){
		if(class_exists($entityClass)){
			$entity = new $entityClass();
		
			$primaryKeys = $entity->primaryKeys();
			$autoIncrmentFields = $entity->autoIncrmentFields();
			$indexs = $entity->indexs();
		
			$sql = "CREATE TABLE IF NOT EXISTS `".$entity->tableName()."` (";
			$first = true;
		
			foreach($entity as $key=>$value){
				if($first){
					$first = false;
				}
				else{
					$sql .= " , ";
				}
				$sql .= "`".$entity->tableField($key)."` ".$entity->tableFieldType($key).(array_search($key,$autoIncrmentFields) !== false ? " AUTO_INCREMENT":"");
			}
		
			if($primaryKeys){
				$sql .=" , PRIMARY KEY (";
				$first = true;
				foreach($primaryKeys as $key){
					if($first){
						$first = false;
					}
					else{
						$sql .= " , ";
					}
					$sql .= "`".$entity->tableField($key)."`";
				}
				$sql .= ")";
			}
			
			if($indexs && is_array($indexs)){
				foreach($indexs as $name=>$index){
					if(is_array($index) ){
						
						$sql .=" , INDEX `{$name}` (";
						$first = true;
						foreach($index as $field){
							if($first){
								$first = false;
							}
							else{
								$sql .= " , ";
							}
							if(is_array($field) && isset($field["field"])){
								$sql .= "`".$entity->tableField($field["field"])."`";
								if(isset($field["order"])){
									$sql .= " ".$field["order"];
								}
							}
							else{
								$sql .= "`".$entity->tableField($field)."`";
							}
						}
						
						$sql .=")";
						
					}
				}
			}
		
			$sql .= ") AUTO_INCREMENT = 1;";
		
			$this->dbAdapter->query($sql);
		
			return true;
		}
		
		return false;
	}

	public function existsEntity($entityClass){
		$result = false;
		if(class_exists($entityClass)){
			$entity  = new $entityClass();
			$sql = "SELECT COUNT(*) FROM `INFORMATION_SCHEMA`.`TABLES` WHERE `TABLE_SCHEMA`='{$this->dbAdapter->getDatabase()}' and `TABLE_NAME`='{$entity->tableName()}'";
			$rs = $this->query($sql);
			if($rs){
				
				if($row = $this->next($rs)){
					$result = intval($row[0]) >0;
				}
				$this->free($rs);
			}
		}
		return $result;
	}
	
	public function registerEntity($entityClass){
		if(class_exists($entityClass)){
			
			$entity  = new $entityClass();
			
			$primaryKeys = $entity->primaryKeys();
			$autoIncrmentFields = $entity->autoIncrmentFields();
			
			$exists = $this->existsEntity($entityClass);
			
			if(!$exists){

				if($this->createEntity($entityClass)){
					
					$rows = $entityClass::defaultEntitys();
					foreach($rows as $row){
						$this->insert($row);
					}
				}
				
			}
			else{
				$rs = $this->query("SELECT COLUMN_NAME,IS_NULLABLE,COLUMN_TYPE,COLUMN_KEY,COLUMN_EXTRA FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='{$this->dbAdapter->getDatabase()}' and `TABLE_TABLE`='{$entity->tableName()}'");
				
				$fields = array();
				
				if($rs){
					
					while($field = $this->next($rs)){
						$fields[$field["COLUMN_NAME"]] = $field;
					}
					
					$this->free($rs);
				}
				
				foreach($entity as $key=>$value){
					$field = $entity->tableField($key);
					$fieldType = $entity->tableFieldType($key);
					if(!isset($fields[$field])){
						$this->query("ALTER TABLE `".$entity->tableName()."` ADD COLUMN `".$field."` ".$fieldType);
					}
				}
			}
			
			return true;
		}
		
	}

	public function parseValue($value = null){
		if($value === null){
			return "null";
		}
		
		if(is_string($value)){
			$v = str_replace("'", "''", $value);
			$v = str_replace("\\", "\\\\", $v);
			$v = str_replace("\r", "\\r", $v);
			$v = str_replace("\n", "\\n", $v);
			$v = str_replace("\t", "\\t", $v);
			return "'".$v."'";
		}
		else if($value === true){
			return "1";
		}
		else if($value === false){
			return "0";
		}
		else if($value === 0){
			return "0";
		}
		else{
			return $this->parseValue(''.$value);
		}
	}
	
	public function parseArrayValue($value,$key=null,$field=null){
		if($value === null){
			if($field !== null){
				return "({$field})";
			}
			return "()";
		}
		
		$rs = "(";
		$isFirst = true;
		if($field !== null){
			$rs .= $field;
			$isFirst = false;
		}
		foreach($value as $item){
			if($isFirst){
				$isFirst = false;
				$rs .= $key !== null ? $this->parseValue($item->$key):$this->parseValue($item);
			}
			else{
				$rs .= ",".($key !== null ? $this->parseValue($item->$key):$this->parseValue($item));
			}
		}
		return $rs.")";
	}

	private function parseValueSql($entity,$key,$value=null){
		if($value === null){
			$value = $entity->$key;
		}
		return $this->parseValue($value);
	}

	public function insert($entity,$forceIncrment=false){
		$sql = "INSERT INTO `".$entity->tableName()."` (";

		$keys = array();
		$autoIncrmentFields = $entity->autoIncrmentFields();

		foreach($entity as $key=>$value){
			if( array_search($key, $autoIncrmentFields) === false
				|| ($forceIncrment && $entity->$key !== null )){
				array_push($keys,$key);
			}
		}

		$count = count($keys);

		for($i=0;$i<$count;$i++){
			if($i !=0){
				$sql .=",";
			}
			$sql .= "`".$entity->tableField($keys[$i])."`";
		}

		$sql .= ") VALUES (";

		for($i=0;$i<$count;$i++){
			if($i !=0){
				$sql .=",";
			}
			$sql .= $this->parseValueSql($entity,$keys[$i]);
		}

		$sql .=")";
		$this->dbAdapter->query($sql);

		$this->lastSql = $sql;
		
		$insertId = $this->dbAdapter->getInsertId();
		if($insertId !== null){
			foreach($autoIncrmentFields as $key){
				$entity->$key = $insertId;
			}
		}

		return $this->dbAdapter->affectedRows();
	}

	public function delete($entity,$keys=null){
		$e = null;
		$kv = $keys;
		if(is_string($entity)){
			$e = new $entity();
		}
		else{
			$e = $entity;
		}
		if($e){
			$primaryKeys = $e->primaryKeys();
			$c = count($primaryKeys);
			if($kv == null){
				$kv = array();
				foreach($primaryKeys as $key){
					$kv[$key] = $e->$key;
				}
			}
			
			if(is_string($kv)){
				$sql = "DELETE FROM `".$e->tableName()."` WHERE ".$kv;
	
				$this->dbAdapter->query($sql);
			}
			else{
				$sql = "DELETE FROM `".$e->tableName()."` WHERE ";
	
				for($i=0; $i < $c;$i++){
					$key = $primaryKeys[$i];
					if($i !=0){
						$sql .=" AND ";
					}
					$sql .= "`".$e->tableField($key)."`=".$this->parseValueSql($e,$key,isset($kv[$key]) ? $kv[$key]:null);
				}
	
				$this->dbAdapter->query($sql);
			}
		
			$this->lastSql = $sql;
			
			return $this->dbAdapter->affectedRows();
		}
	}

	public function update($entity){
		$primaryKeys = $entity->primaryKeys();
		$c = count($primaryKeys);

		$sql = "UPDATE `".$entity->tableName()."` SET ";
		$first = true;

		foreach($entity as $key => $value){
			if(array_search($key,$primaryKeys) === false){
				if($first){
					$first =false;
				}
				else{
					$sql .= ",";
				}
				$sql .= "`".$entity->tableField($key)."` = ".$this->parseValueSql($entity,$key);
			}
		}

		$sql .= " WHERE ";
		for($i=0; $i < $c;$i++){
			$key = $primaryKeys[$i];
			if($i !=0){
				$sql .=" AND ";
			}
			$sql .= "`".$entity->tableField($key)."`=".$this->parseValueSql($entity,$key);
		}
		$this->dbAdapter->query($sql);

		$this->lastSql = $sql;
		
		return $this->dbAdapter->affectedRows();
	}

	public function resultToArray($row,$entityClass,$prefix=null){
		
		$entity = new $entityClass();
		
		$values = array();
		foreach($entity as $key=>$value){
			$dbKey  = $entity->tableField($key);
			if($prefix){
				$dbKey = $prefix.$dbKey;
			}
			$value = isset($row[$dbKey]) ? $row[$dbKey]:null;
			if($value !== null){
				$values[$key] = $value;
			}
		}

		return $values;
	}
	
	public function resultToObject($row,$entityClass,$prefix=null){

		$entity = new $entityClass();
		$values = array();
		foreach($entity as $key=>$value){
			$dbKey  = $entity->tableField($key);
			if($prefix){
				$dbKey = $prefix.$dbKey;
			}
			$value = isset($row[$dbKey]) ? $row[$dbKey]:null;
			if($value !== null){
				$values[$key] = $value;
			}
		}

		foreach($values as $key => $value){
			$entity->$key = $value;
		}

		return $entity;

	}

	public function nextObject($rs,$entityClass,$prefix=null){
		if($row = $this->dbAdapter->next($rs)){
			return $this->resultToObject($row,$entityClass,$prefix);
		}
		return null;
	}

	public function next($rs){
		return $this->dbAdapter->next($rs);
	}

	public function free($rs){
		return $this->dbAdapter->free($rs);
	}

	public function query($sql){
		$this->lastSql = $sql;
		return $this->dbAdapter->query($sql);
	}
	
	public function count($rs){
		return $this->dbAdapter->count($rs);
	}
	
	public function countBySql($sql){
		$rs = $this->query($sql);
		$count = false;
		if($rs){
			$count = $this->count($rs);
			$this->free($rs);
		}
		return $count;
	}
	
	public function countForEntity($entityClass,$where = "1=1"){
		$rs = $this->queryEntitys($entityClass,$where);
		$count = false;
		if($rs){
			$count = $this->count($rs);
			$this->free($rs);
		}
		return $count;
	}

	public function queryEntitys($entityClass,$where = "1=1"){
		$entity = new $entityClass();
		$sql = "SELECT * FROM `".$entity->tableName()."` WHERE ".$where;
		$this->lastSql = $sql;
		return $this->dbAdapter->query($sql);
	}

	public function querySingleEntity($entityClass,$where = "1=1"){
		$entity = new $entityClass();
		$sql = "SELECT * FROM `".$entity->tableName()."` WHERE ".$where;
		$this->lastSql = $sql;
		
		$rs = $this->dbAdapter->query($sql);
		if($rs){
			$entity = $this->nextObject($rs, $entityClass);
			$this->dbAdapter->free($rs);

			return $entity;
		}
		return null;
	}

	public function get($entityClass,$keys){
		$entity = new $entityClass();
		$primaryKeys = $entity->primaryKeys();
		$c = count($primaryKeys);

		$sql = "SELECT * FROM `".$entity->tableName()."` WHERE ";

		for($i=0; $i<$c; $i++){
			$key = $primaryKeys[$i];
			if($i !=0){
				$sql .=" AND ";
			}
			$sql .= "`".$entity->tableField($key)."`=".$this->parseValueSql($entity,$key,isset($keys[$key]) ? $keys[$key] : null);
		}

		$this->lastSql = $sql;
		$rs = $this->dbAdapter->query($sql);
		if($rs){
			$entity  = $this->nextObject($rs,$entityClass);
			$this->dbAdapter->free($rs);
			return $entity;
		}
		return null;
	}

	public function selectFields($entityClass,$prefix=null,$as=null){
		$entity = new $entityClass();
		$fields = "";
		if($as === null){
			$as = $entity->tableName();
		}
		foreach($entity as $key=>$value){
			if($fields !=""){
				$fields .= ",";
			}
			if($prefix){
				$fields .= $as.".".$entity->tableField($key)." as ".$prefix.$entity->tableField($key);
			}
			else{
				$fields .= $as.".".$entity->tableField($key);
			}
		}
		return $fields;
	}
	
	public function commit(){
		$this->dbAdapter->query("commit;");
	}
	
	public function rollback(){
		$this->dbAdapter->query("rollback;");
	}
	
	public function getLastSql(){
		return $this->lastSql;
	}
	
	public function getDBAdapter(){
		return $this->dbAdapter;
	}
	
	public function lockWrite($entityClass){
		
		$names = "";
		
		if(is_array($entityClass)){
			foreach ($entityClass as $clazz){
				$entity = new $clazz();
				if($names == ""){
					$names = "`".$entity->tableName()."`";
				}
				else{
					$names .= ",`".$entity->tableName()."`";
				}
			}
		}
		else{
			$entity = new $entityClass();
			$names = "`".$entity->tableName()."`";
		}
		$this->dbAdapter->query("LOCK TABLES {$names} WRITE;");
	}
	
	public function lockRead($entityClass){
	
		$names = "";
		
		if(is_array($entityClass)){
			foreach ($entityClass as $clazz){
				$entity = new $clazz();
				if($names == ""){
					$names = "`".$entity->tableName()."`";
				}
				else{
					$names .= ",`".$entity->tableName()."`";
				}
			}
		}
		else{
			$entity = new $entityClass();
			$names = "`".$entity->tableName()."`";
		}
		
		$this->dbAdapter->query("LOCK TABLES `{$name}` READ;");
	}
	
	public function unlock(){
		$this->dbAdapter->query("UNLOCK TABLES;");
	}
	
	public function getInsertId(){
		return $this->dbAdapter->getInsertId();
	}
	
	public function errno(){
		return $this->dbAdapter->errno();
	}
	
	public function errmsg(){
		return $this->dbAdapter->errmsg();
	}
}

function defaultDBContext($dbContext){
	$GLOBALS["_defaultDBContext"] = $dbContext;
	return $dbContext;
}

function getDefaultDBContext(){
	return isset($GLOBALS["_defaultDBContext"]) ? $GLOBALS["_defaultDBContext"]:null;
}

?>
