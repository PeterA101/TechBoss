<?php
session_start();

//unsetting the exsisting session credentials
 unset($_SESSION['user_id']);
 unset($_SESSION['email']);
 unset($_SESSION['firstname']);
 unset($_SESSION['lastname']);

// Redirect to login page
header("Location: index.php");
exit;
?>