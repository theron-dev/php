<?php

/**
 * 
 * Service Context
 * @author zhanghailong
 *
 */
interface IServiceContext{
	
	/**
	 * 
	 * task handle
	 * @param Class $taskType
	 * @param Object<Class> $task
	 * 
	 * @return Boolean 
	 */
	public function handle($taskType,$task,$security = true);
	
	/**
	 * 
	 * @return array
	 */
	public function getInputData();
	
	public function getInputDataValue($key);
	
	public function setInputDataValue($key,$value);
	
	/**
	 * 
	 * @return array
	 */
	public function getInternalData();
	
	public function getInternalDataValue($key);
	
	public function setInternalDataValue($key,$value);
	
	
	/**
	 * 
	 * @return array
	 */
	public function getOutputData();
	
	public function getOutputDataValue($key);
	
	public function setOutputDataValue($key,$value);

	public function fillTask($task,$defaultData=null,$namespace=null);
	
	public function fillData(&$data,$task);
	
	public function outputTask($task,$key=null);
	
	public function dbContext($key=null,$partKey=null);
	
}

?>