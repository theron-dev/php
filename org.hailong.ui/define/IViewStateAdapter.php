<?php

interface IViewStateAdapter{

	public function setContext($context);
	
	public function saveViewState($data);
	
	public function loadViewState();
	
}

?>