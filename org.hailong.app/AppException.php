<?php

define("ERROR_APP_NOT_FOUND",ERROR_APP | 0x01);
define("ERROR_APP_NOT_HANDLE_LOGIN_TASK", ERROR_APP | 0x02);
define("ERROR_APP_AUTH_TOKEN",ERROR_APP | 0x03);

class AppException extends Exception{
	
}

?>