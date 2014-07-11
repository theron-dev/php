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
	
	public $results;

}
