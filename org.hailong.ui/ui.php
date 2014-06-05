<?php
/**
* UI
*/
if($library){

	global $UI_SESSION_DIR;

	$UI_SESSION_DIR = "$library/org.hailong.ui/sessions";

	require_once "$library/org.hailong.ui/define/IViewContext.php";
	require_once "$library/org.hailong.ui/define/Action.php";
	require_once "$library/org.hailong.ui/define/View.php";
	require_once "$library/org.hailong.ui/define/RootViewContext.php";
	require_once "$library/org.hailong.ui/define/ViewException.php";
	require_once "$library/org.hailong.ui/define/Shell.php";
	require_once "$library/org.hailong.ui/define/ViewController.php";
	
	require_once "$library/org.hailong.ui/define/IViewStateAdapter.php";
	require_once "$library/org.hailong.ui/define/SessionViewStateAdapter.php";
	require_once "$library/org.hailong.ui/define/CookieViewStateAdapter.php";
	
	require_once "$library/org.hailong.ui/define/controls/TextView.php";
	require_once "$library/org.hailong.ui/define/controls/Button.php";
	require_once "$library/org.hailong.ui/define/controls/Label.php";
	require_once "$library/org.hailong.ui/define/controls/WebView.php";
	require_once "$library/org.hailong.ui/define/controls/ListView.php";
	require_once "$library/org.hailong.ui/define/controls/Toolbar.php";
	require_once "$library/org.hailong.ui/define/controls/TableView.php";
	require_once "$library/org.hailong.ui/define/controls/Dialog.php";
	require_once "$library/org.hailong.ui/define/controls/Form.php";
	require_once "$library/org.hailong.ui/define/controls/Image.php";
	require_once "$library/org.hailong.ui/define/controls/Link.php";
	require_once "$library/org.hailong.ui/define/controls/ActionView.php";
	require_once "$library/org.hailong.ui/define/controls/Html.php";
	require_once "$library/org.hailong.ui/define/controls/Template.php";
}
?>