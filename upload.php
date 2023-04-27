<?php
//Establish connection to the database
$conn = mysqli_connect('localhost', 'root', '', 'compsci');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

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

if (isset($_POST['submit'])) {
    //Get input values
    $question = mysqli_real_escape_string($conn, $_POST['question']);
    $topic = mysqli_real_escape_string($conn, $_POST['topic']);
    $optionA = mysqli_real_escape_string($conn, $_POST['optionA']);
    $optionB = mysqli_real_escape_string($conn, $_POST['optionB']);
    $optionC = mysqli_real_escape_string($conn, $_POST['optionC']);
    $optionD = mysqli_real_escape_string($conn, $_POST['optionD']);
    $answer = mysqli_real_escape_string($conn, $_POST['answer']);
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);

    //Insert values into database
    $sql = "INSERT INTO questions (question, topic, optionA, optionB, optionC, optionD, answer, difficulty) 
    VALUES ('$question', '$topic', '$optionA', '$optionB', '$optionC', '$optionD', '$answer', '$difficulty')";

    if (mysqli_query($conn, $sql)) {
        echo "Question uploaded successfully";

    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Questions</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body class="content">
    <div class="header">
        <h2>Upload Questions</h2>
    </div>
    <form method="post" action="upload.php">
        <div>
            <label>Question:</label>
            <input type="text" name="question" required>
        </div>
        <div>
            <label>Topic:</label>
            <select name="topic" required>
                <option value="science">Science</option>
                <option value="history">History</option>
                <option value="geography">Geography</option>
            </select>
        </div>
        <div>
            <label>Option A:</label>
            <input type="text" name="optionA" required>
        </div>
        <div>
            <label>Option B:</label>
            <input type="text" name="optionB" required>
        </div>
        <div>
            <label>Option C:</label>
            <input type="text" name="optionC" required>
        </div>
        <div>
            <label>Option D:</label>
            <input type="text" name="optionD" required>
        </div>
        <div>
            <label>Answer:</label>
            <select name="answer" required>
                <option value="a">A</option>
                <option value="b">B</option>
                <option value="c">C</option>
                <option value="d">D</option>
            </select>
        </div>
        

        <div class="form-group">
            <label for="difficulty">Difficulty</label>
            <select class="form-control" id="difficulty" name="difficulty" required>
                <option value="" selected disabled>Select difficulty</option>
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
            </select>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</div>
</body>
</html>
<?php
