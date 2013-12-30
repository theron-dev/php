<?php

interface IPublishDataSource{
	
	public function publishDataSourceCount();
	
	public function publishDataSourceId($data);
	
	public function publishDataSourceTimestamp($data);
	
	public function publishDataSourceData($index);
	
}

?>