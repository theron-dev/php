<?php

/**
 * 发布结构运行时
 * @author zhanghailong
 *
 */
class DBPublishSchemaRuntime{
	
	private $context;
	private $dbContext;
	private $domain;
	private $scheam;
	private $tableName;
	private $document;
	private $fields;
	
	public function __construct($context,$dbContext,$domain,$scheam){
		parent::__construct();
		
		$this->context = $context;
		$this->dbContext = $dbContext;
		$this->domain = $domain;
		$this->scheam = $scheam;
		
		$this->tableName = "ps_{$domain}_{$schema->path}_{$schema->version}";
		
		$this->document = new DOMDocument();
		
		@$doc->loadXML($this->scheam->content);
		
		$this->fields = array();
		
		if($this->document){
			$nodeList = $this->document->getElementsByTagName("field");
			for($i=0;$i<$nodeList->length;$i++){
				$element = $nodeList->item($i);
				
				$this->fields[] = $element;
			}
		}
		
		$dbAdapter = $dbContext->getDBAdapter();
		$isExists = false;
		
		$sql = "SELECT COUNT(*) FROM `INFORMATION_SCHEMA`.`TABLES` WHERE `TABLE_SCHEMA`='{$dbAdapter->getDatabase()}' and `TABLE_NAME`='{$this->tableName}'";
		$rs = $this->query($sql);
		
		if($rs){
		
			if($row = $this->next($rs)){
				$isExists = intval($row[0]) >0;
			}
			$this->free($rs);
		}
		
		if($isExists){
			
			$rs = $dbContext->query("SELECT COLUMN_NAME,IS_NULLABLE,COLUMN_TYPE,COLUMN_KEY,COLUMN_EXTRA FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='{$dbAdapter->getDatabase()}' and `TABLE_TABLE`='{$this->tableName}'");
			
			$fields = array();
			
			if($rs){
					
				while($field = $dbContext->next($rs)){
					$fields[$field["COLUMN_NAME"]] = $field;
				}
					
				$dbContext->free($rs);
			}
			
			foreach($this->fields as $field){
				$name = $this->elementAttrValue($field,"name");
				$type = $this->elementAttrValue($field,"type");
				$length = $this->elementAttrValue($field,"length");
				if($name && !isset($fields[$name])){
					$sql = "ALTER TABLE `".$this->tableName."` ADD COLUMN `".$name."` ".$this->sqlTypeForType($type, $length);
					
					$dbContext->query($sql);
				}
			}
			
		}
		else{
			
			$sql = "CREATE TABLE IF NOT EXISTS `".$this->tableName."` (`id` BIGINT NOT NULL AUTO_INCREMENT,`sid` BIGINT";

			foreach($this->fields as $field){
				$name = $this->elementAttrValue($field,"name");
				$type = $this->elementAttrValue($field,"type");
				$length = $this->elementAttrValue($field,"length");
				if($name){
					$sql .= " , `{$name}` ".$this->sqlTypeForType($type, $length);
				}
			}
			
			$sql .= " `timestamp` INT, PRIMARY KEY (`id`)";

			$sql .= ") AUTO_INCREMENT = 1;";
			
			$dbContext->query($sql);
			
		}
	}
	
	public function clear(){
		$this->dbContext->query("DELETE FROM `{$this->tableName}`");
	}
	
	public function put($data,$sid,$timestamp){
		
		$dbContext = $this->dbContext;
		$dbAdapter = $dbContext->getDBAdapter();
		
		$sid = $dbContext->parseValue($sid);
		
		$sql = "SELECT * FROM `{$this->tableName}` WHERE `sid`={$sid}";
		
		$data = null;
		
		$rs = $dbContext->query($sql);
		
		if($rs){
			$data = $dbContext->next($rs);
			$dbContext->free($rs);
		}
		
		if($data){
			
			if($date->timestamp != $timestamp){
				
				$sql = "UPDATE `{$this->tableName}` SET `timestamp`={$timestamp}";

				foreach($this->fields as $field){
					$name = $this->elementAttrValue($field,"name");
					if($name){
						
						$v = null;
						if(isset($data[$name])){
							$v = $data[$name];
						}
						else if(isset($data->$name)){
							$v = $data->$name;
						}
						
							
						if($v){
							if($type == "object"){
								$v = json_encode($v);
							}
						}
						
						$sql .= ",`{$name}`=".$dbContext->parseValue($v);
					}
				}
				
				$sql .=" WHERE `sid`={$sid}";
			
				$dbContext->query($sql);
				
				return true;
			}
		}
		else{
			$sql = "INSERT INTO `{$this->tableName}`(`sid`,`timestamp`";
			
			foreach($this->fields as $field){
				$name = $this->elementAttrValue($field,"name");
				if($name){
					$sql .= ",`{$name}`";
				}
			}
			
			$sql .=") VALUES (".$dbContext->parseValue($sid).",{$timestamp}";
	
			foreach($this->fields as $field){
				$name = $this->elementAttrValue($field,"name");
				$type = $this->elementAttrValue($field, "type");
				if($name){
					
					$v = null;
					if(isset($data[$name])){
						$v = $data[$name];
					}
					else if(isset($data->$name)){
						$v = $data->$name;
					}

					if($v){
						if($type == "object"){
							$v = json_encode($v);
						}
					}
					
					$sql .= ",".$dbContext->parseValue($v);
				}
			}
			
			$sql .=");";
			
			$dbContext->query($sql);
			
			return $dbAdapter->getInsertId();
		}
	}
	
	public function xslt($xslt){
	
		$document = null;
		if($xslt instanceof DOMDocument){
			$document = $xslt;
		}
		else{
			$document = new DOMDocument();
			@$document->loadXML($xslt, LIBXML_NOCDATA);
		}
	
		$proc = new XSLTProcessor();
		
		$proc->importStylesheet( $document );
		
		return $proc->transformToXml($this->document);
	}
	
	public function elementAttrValue($element,$name){
		$n = $element->attributes->getNamedItem($name);
		if($n){
			return $n->nodeValue;
		}
		return null;
	}
	
	public function sqlTypeForType($type,$length){
		if($type == "int"){
			if($length){
				return "INT($length)";
			}
			else{
				return "INT";
			}
		}
		else if($type == "long"){
			if($length){
				return "BIGINT($length)";
			}
			else{
				return "BIGINT";
			}
		}
		else if($type == "double"){
			if($length){
				return "DOUBLE($length)";
			}
			else{
				return "DOUBLE";
			}
		}
		else if($type == "object"){
			if($length){
				return "TEXT($length)";
			}
			else{
				return "TEXT";
			}
		}
		else{
			if($length){
				return "VARCHAR($length)";
			}
			else{
				return "VARCHAR(45)";
			}
		}
	}
	
}

?>