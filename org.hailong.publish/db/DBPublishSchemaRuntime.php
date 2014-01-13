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
		
		$this->tableName = md5("ps_{$domain}_{$schema->path}_{$schema->version}");
		
		$this->document = new DOMDocument();
		
		@$this->document->loadXML($this->scheam->content);
		
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
			
			$sql = "CREATE TABLE IF NOT EXISTS `".$this->tableName."` (`eid` BIGINT NOT NULL AUTO_INCREMENT";

			foreach($this->fields as $field){
				$name = $this->elementAttrValue($field,"name");
				$type = $this->elementAttrValue($field,"type");
				$length = $this->elementAttrValue($field,"length");
				if($name){
					$sql .= " , `{$name}` ".$this->sqlTypeForType($type, $length);
				}
			}
			
			$sql .= " `timestamp` INT(11), PRIMARY KEY (`eid`)";

			$sql .= ") AUTO_INCREMENT = 1;";
			
			$dbContext->query($sql);
			
		}
	}
	
	public function clear(){
		$this->dbContext->query("DELETE FROM `{$this->tableName}`");
	}
	
	public function remove($eid){
		$this->dbContext->query("DELETE FROM `{$this->tableName}` WHERE eid=".intval($eid));
	}
	
	public function add($data,$timestamp){
		
		$dbContext = $this->dbContext;
		$dbAdapter = $dbContext->getDBAdapter();
		
		if(!$timestamp){
			$timestamp = time();
		}
		
		$sql = "INSERT INTO `{$this->tableName}`(`timestamp`";
		
		foreach($this->fields as $field){
			$name = $this->elementAttrValue($field,"name");
			if($name){
				$sql .= ",`{$name}`";
			}
		}
		
		$sql .=") VALUES ({$timestamp}";

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
	
	public function releaseTo($todir){
		
		$todir .= "/".$this->domain->domain."/".$this->scheam->path."/".$this->scheam->version;
		
		if(!file_exists($todir)){
			mkdir($todir,null,true);
		}
		
		$xmlContent = '<?xml version="1.0" encoding="UTF-8"?><data>';

		$sql = "SELECT * FROM `{$this->tableName}` ORDER BY `timestamp` DESC";
		
		$rs = $this->dbContext->query($sql);
		
		if($rs){
			
			while($row = $dbContext->next($rs)){
				
				$xmlContent .= "<item";
				
				foreach($this->fields as $field){
					$name = $this->elementAttrValue($field,"name");
					if($name && isset($row[$name])){
						$xmlContent .= ' '.$name.'="'.htmlspecialchars($row[$name]).'"';
					}
				}
				
				$xmlContent .= "></item>";
				
			}
			
			$dbContext->free($rs);
			
		}
		
		$xmlContent .= '</data>';
		
		file_put_contents($todir.'/data.xml', $xmlContent);
		
		$data = new DOMDocument();
		
		@$data->loadXML($xmlContent);
		
		$dbContext = $this->context->dbContext(DB_PUBLISH);
		
		$rs = $dbContext->queryEntitys("DBPublishSchemaEntity","psid={$this->scheam->psid}");
		
		if($rs){
			
			while($entity = $dbContext->nextObject($rs,"DBPublishSchemaEntity")){
				
				if($entity->content){
					
					if($entity->entityType == DBPublishSchemaEntityTypeHtml || $entity->entityType == DBPublishSchemaEntityTypeXml){
						
						$proc = new XSLTProcessor();
						
						$xsl = new DOMDocument();
						
						@$xsl->loadXML($entity->content);
						
						$proc->importStylesheet( $xsl );
						
						$xml = $proc->transformToXml($doc);
						
						if($xml !== false){
							
							$ext = $entity->entityType == DBPublishSchemaEntityTypeHtml ? '.html' : '.xml';
							
							file_put_contents($todir.'/'.$entity->name.$ext, $xml);
							
						}
						
					}
					
				}
				
			}
			
			$dbContext->free($entity);
		}
		
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