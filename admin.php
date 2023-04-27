<?php
session_start();
if (!isset($_SESSION['username'])) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login.php');
}
if ($_SESSION['admin'] != 1) {
  $_SESSION['msg'] = "You don't have permission to access this page";
  header('location: login.php');
}
$conn = mysqli_connect('localhost', 'root', '', 'compsci');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_POST['delete_question'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM questions WHERE id=$id";
    mysqli_query($conn, $sql);
  }
  
  // Retrieve all questions from database
  $sql = "SELECT * FROM questions";
  $result = mysqli_query($conn, $sql);
  
  ?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Only Page</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div class="table">
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Question</th>
        <th>Topic</th>
        <th>Option A</th>
        <th>Option B</th>
        <th>Option C</th>
        <th>Option D</th>
        <th>Answer</th>
        <th>Difficulty</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo $row['Question']; ?></td>
          <td><?php echo $row['Topic']; ?></td>
          <td><?php echo $row['OptionA']; ?></td>
          <td><?php echo $row['OptionB']; ?></td>
          <td><?php echo $row['OptionC']; ?></td>
          <td><?php echo $row['OptionD']; ?></td>
          <td><?php echo $row['Answer']; ?></td>
          <td><?php echo $row['Difficulty']; ?></td>
          <td>
            <form method="post">
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <button type="submit" name="delete_question">Delete</button>
            </form>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
  <p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
  </div>
</body>
</html>