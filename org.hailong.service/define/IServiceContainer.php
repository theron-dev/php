<?php

/**
 * 
 * Service Container
 * @author zhanghailong
 *
 */
interface IServiceContainer{
	
	/**
	 * 
	 * @param Class $taskType
	 */
	public function addTaskType($taskType);
	
	/**
	 * 
	 * @param Class $taskType
	 */
	public function removeTaskType($taskType);
	
	/**
	 * 
	 * @return Boolean
	 */
	public function isInstance();
		
	/**
	 * Create IService Instance
	 */
	public function createInstance();
	
	/**
	 * 
	 * @param array $config
	 */
	public function setConfig($config);
	
	/**
	 * 内部安全
	 * @param boolean $security
	 */
	public function setSecurity($security);
	

	public function getSecurity();
		
}

?>