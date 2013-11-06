<?php 

define("CLIENT_SIGN_TOKEN","$%^&*HBNLO(*");

function client_sign(){
	return md5(CLIENT_SIGN_TOKEN.$_SERVER["REMOTE_ADDR"].$_SERVER["HTTP_USER_AGENT"].CLIENT_SIGN_TOKEN);
}

?>