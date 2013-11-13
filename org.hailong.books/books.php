<?php
/**
 * Books
 */
if($library){

	define("DB_BOOKS","books");

	require_once "$library/org.hailong.configs/error_code.php";
	require_once "$library/org.hailong.service/service.php";
	require_once "$library/org.hailong.account/account.php";
	
	require_once "$library/org.hailong.books/BooksException.php";
	
	require_once "$library/org.hailong.books/db/DBBooks.php";
	
	require_once "$library/org.hailong.books/tasks/BooksTask.php";
	require_once "$library/org.hailong.books/tasks/BooksAuthTask.php";
	require_once "$library/org.hailong.books/tasks/BooksCreateTask.php";
	require_once "$library/org.hailong.books/tasks/BooksQueryTask.php";
	
	require_once "$library/org.hailong.books/services/BooksService.php";
}
?>