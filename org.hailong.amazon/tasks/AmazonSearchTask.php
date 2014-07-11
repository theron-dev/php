<?php

class AmazonSearchTask extends AmazonTask{
	
	public $category;
	public $page;
	public $keyword;
	
	/**
	 * 'Large', 'Small'
	 * @var array
	 */
	public $responseGroup;
	
	public $results;
	
}
