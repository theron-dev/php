<?php

define("ERROR_COIN_NOT_FOUND_UID",ERROR_COIN | 0x0001);
define("ERROR_COIN_NOT_FOUND",ERROR_COIN | 0x0002);
define("ERROR_COIN_NOT_ENOUGH",ERROR_COIN | 0x0003);

class CoinException extends Exception{
	
}

?>