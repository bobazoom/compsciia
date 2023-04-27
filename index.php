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
		<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-K8PEFHLBXF"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'G-K8PEFHLBXF');
</script>
</head>
<body>

<div class="header">
	<h1>Quiz Database</h1>
</div>
	
<div class="content">
<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

	
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p><br>
		<a href="/ia/quiz.php">Quiz</a><br>
		<a href="/ia/weather.php">Weather</a><br>
		<a href="/ia/results.php">Results</a><br>
		<a href="/ia/others.php">Other Relevant Webpages</a><br>
		<a href="/ia/upload.php">Question Upload</a><br>
		<?php  if (isset($_SESSION['admin'])) : ?>
			<a href="/ia/admin.php">Admin Page</a><br>
		<?php endif ?>
    	<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
</div>

</body>
</html>