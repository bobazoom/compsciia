<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
	<h1>Resources</h1>
</div>
<div class="content">
    <a rel=“nofollow” href="https://www.khanacademy.org/">Khan Academy</a><br>
    <a rel=“nofollow” href="https://quizlet.com">Quizlet</a><br>
    <a rel=“nofollow” href="https://www.edx.org">Edx</a><br>
</div>
    


    
