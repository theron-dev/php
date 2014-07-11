<?php

class AmazonLookupTask extends AmazonTask{
	
	/**
	 * 
	 * @var String
	 */
	public $itemId;
	
	/**
	 * 'asin' default,'isbn'
	 * @var String
	 */
	public $itemIdType;
	
	/**
	 * 'Large', 'Small'
	 * @var array
	 */
	public $responseGroup;
	
	public $results;

}
