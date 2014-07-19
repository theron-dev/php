<?php

class AmazonLookupTask extends AmazonTask{
	
	/**
	 * 
	 * @var String
	 */
	public $itemId;
	
	/**
	 * 'asin' default,'isbn','upc','ean'
	 * @var String
	 */
	public $itemIdType;
	
	public $category;
	/**
	 * 'Large', 'Small'
	 * @var array
	 */
	public $responseGroup;
	
	public $results;

}
