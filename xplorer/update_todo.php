<?php
session_start();
var_dump($_POST['markasdone_button']);
?>

<?php
session_start();
include_once 'apicaller.php';

$apicaller = new ApiCaller('APP001', '28e336ac6c9423d946ba02d19c6a2632', 'http://localhost/simpletodo_api/');

$mark_as_done = (isset($_POST['markasdone_button'])) ? 'true' : $_POST['is_done'];

$new_item = $apicaller->sendRequest(array(
	'controller' => 'todo',
	'action' => 'update',
	'todo_id' => $_POST['todo_id'],
	'title' => $_POST['title'],
	'due_date' => $_POST['due_date'],
	'description' => $_POST['description'],
	'is_done' => $mark_as_done,
	'username' => $_SESSION['username'],
	'userpass' => $_SESSION['userpass']
));

header('Location: todo.php');
exit();
?>