<?php

global $library;

if(!$library){
	$library = "..";
}

$config =  require("$library/org.hailong.configs/ui_url.php");

header("Content-Type: text/javascript; charset=UTF-8;");
?>

(function(){

var domain = document.domain;
var index = domain.indexOf(".");
document.domain = domain.substr(index +1);

Config = {
	baseUrl:'<?php echo $config?>',
	resourceUrl:'<?php echo require("$library/org.hailong.configs/resource_url.php")?>'
};

document.write("<script type='text/javascript' src='<?php echo $config?>/js/jquery-1.6.min.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/jquery-ui-1.8.21.custom.min.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/jquery.layout.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/engine.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/view.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/TextView.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/Label.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/Button.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/LinkButton.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/WebView.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/TableView.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/Select.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/Form.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/DateText.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/Link.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/ActionView.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/Dialog.js'></script>");
document.write("<script type='text/javascript' src='<?php echo $config?>/js/controls/Template.js'></script>");
document.write("<link rel='stylesheet' href='<?php echo $config?>/css/ui-darkness/jquery-ui-1.8.21.custom.css' />");

})();
