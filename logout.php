<?php
setcookie('username', $_SERVER['PHP_AUTH_USER'], time() - 2592000, '/');
$_SERVER['PHP_AUTH_USER'] = '';
$_SERVER['PHP_AUTH_PW'] = '';
echo "Click <a href='222indexA.php'>here to go back to the index</a>.<br />";
echo "You must close your browser before you will be logged out."; 
?>