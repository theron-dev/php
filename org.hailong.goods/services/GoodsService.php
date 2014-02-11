<?php

/**
 * 物品服务
 * @author zhanghailong
 * @task GoodsImportTask , GoodsCreateTask , GoodsForwardTask , GoodsLikeTask , GoodsResetClassifyTask
 */
class GoodsService extends Service{
	
	public function handle($taskType,$task){
		
		if($task instanceof GoodsCreateTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_GOODS);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$uid = $task->uid;
			
			if($uid === null){
				$uid = $context->getInternalDataValue("auth");
			}
			
			$item = $dbContext->querySingleEntity("DBGoods","etype={$task->etype} and eid={$task->eid}");
				
			if(!$item){

				$item = new DBGoods();
		
				$item->etype = $task->etype;
				$item->eid = $task->eid;
				$item->title = $task->title;
				$item->image = $task->image;
				$item->price = $task->price;
				$item->source = $task->source;
				$item->uid = $uid;
				$item->unit = $task->unit;
				$item->likeCount = 0;
				$item->forwardCount = 0;
				$item->browseCount = 0;
				$item->url = $task->url;
				$item->wapUrl = $task->wapUrl;
				$item->sid = $task->sid;
				
				if(!$task->sid){
					$t = new GoodsSpreadAskTask();
					$t->etype = $task->etype;
					$t->eid = $task->eid;
					
					$context->handle("GoodsSpreadAskTask", $t);
					
					if($t->sid){
						$item->sid = $t->sid;	
					}
					if($t->url){
						$item->url = $t->url;
					}
					if($t->wapUrl){
						$item->wapUrl = $t->wapUrl;
					}
					if($t->title){
						$item->title = $t->title;
					}
					if($t->image){
						$item->image = $t->image;
					}
					if($t->price){
						$item->price = $t->price;
						$item->unit = $t->unit;
					}
					if($t->body){
						$item->body = $t->body;
					}
					if($t->images){
						$task->images = $t->images;
					}
				}
				
				$item->updateTime = time();
				$item->createTime = time();
				
				if(!$item->image){
					throw new GoodsException("goods not found image", ERROR_GOODS_IMAGE_NOT_FOUND);
				}
				
				if(!$item->url && ! $item->wapUrl){
					throw new GoodsException("goods not found url or wapUrl", ERROR_GOODS_URL_NOT_FOUND);
				}

				$dbContext->insert($item);
				
				if(count($task->images) >0){
					foreach($task->images as $image){
						if($image){
							$img = new DBGoodsImage();
							$img->gid = $item->gid;
							$img->url = $image->url;
							$img->createTime = time();
							$dbContext->insert($img);
						}
					}
				}
				
				$t = new ClassifyMatchTask();
				$t->body = $item->title." ".$item->body;
					
				$context->handle("ClassifyMatchTask",$t);
				
				if($t->results){
					foreach($t->results as $cid){
						$c = new DBGoodsClassify();
						$c->cid = $cid;
						$c->gid = $item->gid;
						$c->type = DBGoodsClassifyTypeNone;
						$c->createTime = time();
						$dbContext->insert($c);
					}
				}
				
				$t = new TagMatchTask();
				$t->body = $item->title." ".$item->body;;
				$context->handle("TagMatchTask",$t);
				if($t->results){
					foreach($t->results as $tid){
						$c = new DBGoodsKeyword();
						$c->tid = $tid;
						$c->gid = $item->gid;
						$c->createTime = time();
						$dbContext->insert($c);
					}
				}
			}
			else{
				
				if($task->body){
					$item->body = $task->body;
				}
				
				if($task->price){
					$item->price = $task->price;
				}
				
				if($task->title){
					$item->title = $task->title;
				}

				if($task->image){
					$item->image = $task->image;
				}
				
				$item->updateTime = time();
				
				$dbContext->update($item);
			}
			
			$task->results = $item;

			return false;
		}
		

		if($taskType == "GoodsImportTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_GOODS);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			if($task->etype && $task->eid){
				
				$t = new GoodsSpreadAskTask();
				
				$t->etype = $task->etype;
				$t->eid = $task->eid;
				$t->outerCode = $task->outerCode;
				
				$context->handle("GoodsSpreadAskTask",$t);
				
				$c = new GoodsCreateTask();
				$c->etype = $t->etype;
				$c->eid = $t->eid;
				$c->image = $t->image;
				$c->price = $t->price;
				$c->unit = $t->unit;
				$c->sid = $t->sid;
				$c->source = $t->source;
				$c->title = $t->title;
				$c->uid = $task->uid;
				$c->url = $t->url;
				$c->wapUrl = $t->wapUrl;
				$c->images = $t->images;
				
				$context->handle("GoodsCreateTask",$c);
			}
			else{
				$url = trim($task->url);
				
				if(strpos($url, "http://item.taobao.com/item.htm") === 0){
					$task->results = GoodsService::parseItemURL($context,$dbContext,$url,"taobao",DBGoodsExternTypeTaobao, "xsl/taobao_item.xsl");
				}
				else if(strpos($url,"http://a.m.taobao.com/") === 0){
					$task->results = GoodsService::parseItemURL($context,$dbContext,$url,"mtaobao",DBGoodsExternTypeTaobao, "xsl/mtaobao_item.xsl");
				}
				else if(strpos($url,"http://a.m.tmall.com/i") === 0){
					$task->results = GoodsService::parseItemURL($context,$dbContext,$url,"mtaobao",DBGoodsExternTypeTmall, "xsl/mtaobao_item.xsl");
				}
				else if(strpos($url,"http://auction1.m.taobao.com/") === 0){
					$task->results = GoodsService::parseItemURL($context,$dbContext,$url,"mtaobao",DBGoodsExternTypeTaobao, "xsl/mtaobao_item.xsl");
				}
				else if(strpos($url,"http://detail.tmall.com/item.htm") === 0){
					$task->results = GoodsService::parseItemURL($context,$dbContext,$url,"tmall",DBGoodsExternTypeTmall, "xsl/tmall_item.xsl");
				}
				else{
					throw new GoodsException("does not support item url {$url}", ERROR_GOODS_NOT_SUPPORT_ITEM_URL);
				}
				
				
			}
			
			return false;
		}
		
		if($taskType == "GoodsQueryTask"){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_GOODS);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$cids = false;
			
			if($task->cids == -1){
		
			}
			else if(is_string($task->cids)){
				$cids = preg_split("/[\, ;\/]/i", $task->cids);
			}
			else if(is_array($task->cids) && count($task->cids)){
				$cids = array();
				foreach($task->cids as $cid){
					if($cid instanceof DBClassify){
						$cids[] = $cid->cid;
					}
					else{
						$cids[] = $cid;
					}
				}
			}
			
			$sql = "SELECT g.gid as gid ,MIN(g.title) as title,MIN(g.uid) as uid,MIN(g.image) as image ,MIN(g.price) as price,MIN(g.url) as url,MIN(g.wapUrl) as wapUrl,MIN(g.likeCount) as likeCount,MIN(g.forwardCount) as forwardCount,MIN(g.browseCount) as browseCount,MIN(g.etype) as etype,MIN(g.eid) as eid,MIN(g.sid) as sid, MIN(g.createTime) as createTime "
				." FROM ".DBGoodsClassify::tableName()." as gc RIGHT JOIN ".DBGoods::tableName()." as g ON gc.gid=g.gid WHERE 1=1 ";
			
			if($cids){
				$sql .= " AND gc.cid IN ".$dbContext->parseArrayValue($cids);
			}
			else if($task->cids == -1){
				$sql .=" AND isnull(gc.cid)";
			}
			
			if($task->keyword){
				$sql .= " AND g.title LIKE '%{$task->keyword}%'";
			}
			
			$offset = ($task->pageIndex -1) *  $task->pageSize;
			
			$sql .= " GROUP BY g.gid ORDER BY likeCount + forwardCount DESC,browseCount DESC,count(*) DESC,g.gid DESC ";
			
			$task->total = $dbContext->countBySql($sql);
			
			$sql .= "LIMIT {$offset},{$task->pageSize}";
			
			$rs = $dbContext->query($sql);
			
			if($rs){
				$task->results = array();
				
				while($goods = $dbContext->nextObject($rs,"DBGoods")){
					$task->results[] = $goods;
				}
				
				$dbContext->free($rs);
			}
			
			return false;
		}
		
		if($task instanceof GoodsForwardTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_GOODS);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$dbContext->query("UPDATE ".DBGoods::tableName()." SET forwardCount=forwardCount+1 WHERE gid={$task->gid}");
			
			return false;
		}
		
		if($task instanceof GoodsLikeTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_GOODS);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$dbContext->query("UPDATE ".DBGoods::tableName()." SET likeCount=likeCount+1 WHERE gid={$task->gid}");
				
			return false;
		}
		
		if($task instanceof GoodsResetClassifyTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_GOODS);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$item = $dbContext->get("DBGoods",array("gid"=>$task->gid));
			
			if($item){
				
				$dbContext->delete("DBGoodsClassify","gid={$item->gid}");
				
				$t = new ClassifyMatchTask();
				$t->body = $item->title." ".$item->body;
				
				$context->handle("ClassifyMatchTask",$t);
				
				if($t->results){
					foreach($t->results as $cid){
						$c = new DBGoodsClassify();
						$c->cid = $cid;
						$c->gid = $item->gid;
						$c->type = DBGoodsClassifyTypeNone;
						$c->createTime = time();
						$dbContext->insert($c);
					}
				}
			
			}
			
			return false;
		}
		
		if($task instanceof GoodsAllResetClassifyTask){
				
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_GOODS);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
				
			$rs = $dbContext->queryEntitys("DBGoods");
				
			if($rs){
		
				while($item = $dbContext->nextObject($rs, "DBGoods")){
					
					$dbContext->delete("DBGoodsClassify","gid={$item->gid} and type=".DBGoodsClassifyTypeNone);
					
					$t = new ClassifyMatchTask();
					$t->body = $item->title." ".$item->body;
					
					$context->handle("ClassifyMatchTask",$t);

					if($t->results){
						foreach($t->results as $cid){
							$c = new DBGoodsClassify();
							$c->cid = $cid;
							$c->gid = $item->gid;
							$c->type = DBGoodsClassifyTypeNone;
							$c->createTime = time();
							$dbContext->insert($c);
						}
					}
					
				}
				
				$dbContext->free($rs);
			}
				
			return false;
		}
		
		if($task instanceof GoodsBrowserTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_GOODS);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$t = new CacheGetTask();
			$t->path = CACHE_GOODS_BROWSER;
				
			$context->handle("CacheGetTask", $t);
				
			$bCache = array();
			$bTimestamp = time();
				
			if($t->value){
				$bCache = $t->value;
				$bTimestamp = $t->timestamp;
			}
				
			if(isset($bCache[$task->gid])){
				$bCache[$task->gid] ++;
			}
			else{
				$bCache[$task->gid] = 1;
			}
				
			$config = $this->getConfig();
			$timeout = isset($config["cache-timeout"]) ? $config["cache-timeout"] : 3600;
			$limit =  isset($config["cache-limit"]) ? $config["cache-limit"] : 10;
			
			if(count($bCache) > $limit || time() - $bTimestamp > $timeout){
				foreach($bCache as $gid => $count){
					$dbContext->query("UPDATE ".DBGoods::tableName()." SET browseCount=browseCount+{$count} WHERE gid={$gid}");
				}
				$t = new CachePutTask();
				$t->path = CACHE_GOODS_BROWSER;
				$t->value = array();
			
				$context->handle("CachePutTask", $t);
			}
			else{
				$t = new CachePutTask();
				$t->path = CACHE_GOODS_BROWSER;
				$t->value = $bCache;
			
				$context->handle("CachePutTask", $t);
			}
			
			return false;
		}
		
		if($task instanceof GoodsGetTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
			
			$dbContextTask = new DBContextTask(DB_GOODS);
			
			$context->handle("DBContextTask",$dbContextTask);
			
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$task->results = $dbContext->get("DBGoods",array("gid"=>$task->gid));
			
			return false;
		}
		
		if($task instanceof GoodsTagsTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_GOODS);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$sql = "SELECT g.gid as gid,GROUP_CONCAT(t.tag separator ',') as tags "
			." FROM ".DBGoodsKeyword::tableName()." as g LEFT JOIN ".DBTag::tableName()." as t ON g.tid=t.tid"
			." WHERE g.gid={$task->gid} "
			." GROUP BY g.gid ORDER BY t.weight DESC";
			
			$rs = $dbContext->query($sql);
			
			if($rs){
				if($row = $dbContext->next($rs)){
					$task->tags = $row["tags"];
				}
				$dbContext->free($rs);
			}
			
			return false;
		}
		
		if($task instanceof GoodsKeywordSetTask){
			
			$context = $this->getContext();
			$dbContext = $context->dbContext();
				
			$dbContextTask = new DBContextTask(DB_GOODS);
				
			$context->handle("DBContextTask",$dbContextTask);
				
			if($dbContextTask->dbContext){
				$dbContext = $dbContextTask->dbContext;
			}
			
			$gid = intval($task->gid);
			
			$item = $dbContext->get("DBGoods", array("gid"=>$gid));
			
			if($item){
				
				$dbContext->delete("DBGoodsKeyword","gid={$gid}");
				
				$tags =  preg_split("/[\, ;\/]/i", $task->keyword);
				
				$t = new TagAssignTask();
				$t->inc = 1;
				
				foreach($tags as $tag){
					
					$t->tag = $tag;
	
					$context->handle("TagAssignTask",$t);
					
					$k = new DBGoodsKeyword();
					$k->gid = $gid;
					$k->tid = $t->tid;
					
					$dbContext->insert($k);
				}
			}
		}
		
		return true;
	}
	
	public static function parseItemURL($context,$dbContext,$url,$source,$etype,$xslFile){
		
		global $library;
		
		$curl = curl_init($url);
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		
		$htmlSource = curl_exec($curl);
		
		$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
		
		if($htmlSource && $httpCode == 200){
		
			$html = new DOMDocument();
		
			@$html->loadHTML("<meta http-equiv='Content-Type' content='{$contentType}'>".$htmlSource);
		
			$xslt = new XSLTProcessor();
			$xsl = new DOMDocument();
			if($library){
				@$xsl->load("$library/org.hailong.goods/{$xslFile}", LIBXML_NOCDATA);
			}
			else{
				@$xsl->load($xslFile, LIBXML_NOCDATA);
			}
			
			@$xslt->importStylesheet( $xsl );
		
			header("Content-Type: text/xml;charset=utf8");
		
			$rs = @$xslt->transformToDoc( $html );
			
			if($rs){
				
				$title = false;
				$eid = false;
				$image = false;
				$price = false;
				$body = false;
				
				$nodes = $rs->getElementsByTagName("title");
				
				if($nodes && $nodes->length >0){
					$node = $nodes->item(0);
					$title=  $node->textContent;
				}
				
				$nodes = $rs->getElementsByTagName("image");
				
				if($nodes && $nodes->length >0){
					$node = $nodes->item(0);
					$image=  $node->textContent;
				}
				
				$nodes = $rs->getElementsByTagName("id");
				
				if($nodes && $nodes->length >0){
					$node = $nodes->item(0);
					$eid=  $node->textContent;
				}
				
				$nodes = $rs->getElementsByTagName("price");
				
				if($nodes && $nodes->length >0){
					$node = $nodes->item(0);
					$price=  $node->textContent;
				}
				
				$nodes = $rs->getElementsByTagName("body");
				
				if($nodes && $nodes->length >0){
					$node = $nodes->item(0);
					$body =  $rs->saveHTML($node);
				}

				if($eid){
					
					if($url){
						$task = new URITask();
						$task->url = $url;
						
						$context->handle("URITask",$task);
						
						if($task->uri){
							$url = $task->uri;
						}
					}
					
					$task = new GoodsCreateTask();
					$task->etype = $etype;
					$task->eid = $eid;
					$task->title = $title;
					$task->image = $image;
					$task->price = $price;
					$task->source = $source;
					$task->url = $url;
					$task->wapUrl = $url;
					$task->body = $body;
					
					$context->handle("GoodsCreateTask",$task);
					
					return $task->results;
				}
				else{
					throw new GoodsException("item id xslt error: {$rs->saveHTML()}",ERROR_GOODS_ITEM_XSLT);
				}
				
			}
			else{
				throw new GoodsException("item xslt error: {$xslFile}",ERROR_GOODS_ITEM_XSLT);
			}
		}
		else{
			throw new GoodsException("item url http error httpCode: ".$httpCode,ERROR_GOODS_ITEM_URL_HTTP);
		}
	}
}

?>