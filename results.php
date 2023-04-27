<?php

// Establish connection to the database
$conn = mysqli_connect('localhost', 'root', '', 'compsci');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
session_start(); 

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}

// Get the username of the logged-in user (assuming you have a login system in place)
$username = $_SESSION['username'];

// Query the database to get the user's quiz results
$sql = "SELECT * FROM results WHERE username='$username'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Results</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
    <h1>Results</h1>
</div>
<a href="/ia/index.php">Return to Homepage</a><br>
<?php

// Display the results in an HTML table
echo "<div class=table>";
echo "<table>";
echo "<tr><th>ID</th><th>Username</th><th>Score</th><th>Date/Time</th><th>Topic</th><th>Difficulty</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    // Convert the date and time to a human-readable format
    $date_time = date('F j, Y g:i A', strtotime($row['date_time']));
    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['username'] . "</td><td>" . $row['score'] . "</td><td>" . $date_time . "</td><td>" . $row['topic'] . "</td><td>" . $row['difficulty'] . "</td></tr>";
}
echo "</table>";

// Close database connection
mysqli_close($conn);

?>
