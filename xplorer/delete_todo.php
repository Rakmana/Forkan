<?php
session_start();
include_once 'apicaller.php';

$apicaller = new ApiCaller('APP001', '28e336ac6c9423d946ba02d19c6a2632', 'http://localhost/simpletodo_api/');

$new_item = $apicaller->sendRequest(array(
	'controller' => 'todo',
	'action' => 'delete',
	'todo_id' => $_GET['todo_id'],
	'username' => $_SESSION['username'],
	'userpass' => $_SESSION['userpass']
));

header('Location: todo.php');
exit();
?>