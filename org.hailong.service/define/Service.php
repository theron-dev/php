<?php

class Service implements IService{
	
	private $context;
	private $config;
	
	/**
	*
	* @return IServiceContext
	*/
	public function getContext(){
		return $this->context;
	}
	
	/**
	 *
	 * @param IServiceContext $context
	 */
	public function setContext($context){
		$this->context = $context;
	}
	
	/**
	 *
	 * @return array
	 */
	public function getConfig(){
		return $this->config;
	}
	
	/**
	 *
	 *
	 * @param array $config
	 */
	public function setConfig($config){
		$this->config = $config;
	}
	
	/**
	 *
	 * task handle
	 * @param Class $taskType
	 * @param Object<Class> $task
	 *
	 * @return Boolean
	 */
	public function handle($taskType,$task){
		return true;
	}
	
}
?>
