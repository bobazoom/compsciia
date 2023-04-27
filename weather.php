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
	<h1>Weather</h1>
</div>
<?php
// The URL of the weather API endpoint
$url = "https://data.weather.gov.hk/weatherAPI/opendata/weather.php?dataType=flw&lang=en";

// Retrieve the JSON data using a GET request
$json = file_get_contents($url);

// Convert the JSON string into a PHP array
$data = json_decode($json, true);

// Access the weather data
$generalSituation = $data['generalSituation'];
$forecastPeriod = $data['forecastPeriod'];
$forecastDesc = $data['forecastDesc'];
$outlook = $data['outlook'];
$updateTime = $data['updateTime'];
?>

<!-- HTML to display the weather data -->
<div class="content">
    <a href="/ia/index.php">Return to Homepage</a><br>
  
    <h2><?php echo $forecastPeriod ?></h2><br>
  <p><strong>General Situation:</strong> <?php echo $generalSituation ?></p><br>
  <p><strong>Forecast Description:</strong> <?php echo $forecastDesc ?></p><br>
  <p><strong>Outlook:</strong> <?php echo $outlook ?></p><br>
  <p><strong>Last Updated:</strong> <?php echo $updateTime ?></p><br>
  <p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
</div>