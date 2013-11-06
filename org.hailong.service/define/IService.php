<?php

/**
 * 
 * Service
 * @author zhanghailong
 *
 */
interface IService{

	/**
	 * 
	 * @return IServiceContext 
	 */
	public function getContext();

	/**
	 * 
	 * @param IServiceContext $context
	 */
	public function setContext($context);
	
	/**
	 * 
	 * @return array 
	 */
	public function getConfig();

	/**
	 * 
	 * 
	 * @param array $config
	 */
	public function setConfig($config);
	
	/**
	 * 
	 * task handle
	 * @param Class $taskType
	 * @param Object<Class> $task
	 * 
	 * @return Boolean 
	 */
	public function handle($taskType,$task);
}

?>