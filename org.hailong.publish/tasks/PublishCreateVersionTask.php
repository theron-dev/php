<?php

/**
* 创建新版本任务
* @author zhanghailong
*
*/
class PublishCreateVersionTask extends AuthTask{
	
	/**
	 * 创建目标
	 * domain
	 * domain/path
	 * domain/path/version
	 * @var String
	 */
	public $target;

	/**
	 * 新版本号
	 * @var String
	 */
	public $version;
	
	/**
	 * 标题
	 * @var String
	 */
	public $title;
	
	/**
	 * 说明
	 * @var String
	 */
	public $body;
	
	/**
	 * 输出
	 * @var DBPublishDomain
	 */
	public $domain;
	/**
	 * 输出
	 * @var DBPublishSchema
	 */
	public $schema;
	
	public function __construct($target=null,$version=null){
		$this->target = $target;
		$this->version = $version;
	}
}

?>

