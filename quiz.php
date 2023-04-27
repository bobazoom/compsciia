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
  
//Execute when the user submits the "Start Quiz" form
if (isset($_POST['submit'])) {
    //Retrieve the topic and difficulty selected by the user
    $topic = mysqli_real_escape_string($conn, $_POST['topic']);
    $difficulty = mysqli_real_escape_string($conn, $_POST['difficulty']);
    $_SESSION['topic'] = $topic;
    $_SESSION['difficulty'] = $difficulty;
    //Build a query to retrieve all questions from the database with the selected topic and difficulty
    $questions_query = "SELECT * FROM questions WHERE Topic='$topic' AND Difficulty='$difficulty'";
    
    //Execute the query and store the results in a variable
    $result = mysqli_query($conn, $questions_query);
    
    //Create an array to store all the retrieved questions
    $questions = array();
    
    //Loop through each row in the query result and add it to the $questions array
    while ($row = mysqli_fetch_assoc($result)) {
        $questions[] = $row;
    }
}

//Execute when the user submits the "Submit Quiz" form

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>Quiz Page</title>
    </head>
<body>
    <h1>Quiz Page</h1>
    <h3> Timer:   <div class="counter">60</div></h3>

  <script>
    const counter = document.querySelector('.counter');
    let count  = 60;

    const i = setInterval(() => {
      count  -=1;
      counter.innerText = count ;
      if(count === 0) clearInterval(i);
    }, 1000);
  </script>
    <?php if (!isset($_POST['submit'])) { ?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="topic">Select a topic:</label>
    <select name="topic" id="topic">
        <option value="History">History</option>
        <option value="Science">Science</option>
        <option value="Geography">Geography</option>
    </select>
    
    <br><br>
    
    <label for="difficulty">Select a difficulty:</label>
    <select name="difficulty" id="difficulty">
        <option value="Easy">Easy</option>
        <option value="Medium">Medium</option>
        <option value="Hard">Hard</option>
    </select>
    
    <br><br>
    
    <input type="submit" name="submit" value="Start Quiz">
</form>
<?php } else { ?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <?php foreach ($questions as $question) { ?>
        <input type="hidden" name="question_id[]" value="<?php echo $question['id']; ?>">
        <input type="hidden" name="question[]" value="<?php echo $question['Question']; ?>">
        <input type="hidden" name="option_a[]" value="<?php echo $question['OptionA']; ?>">
<h2><?php echo $question['Question']; ?></h2>
<p><label><input type="radio" name="answer[<?php echo $question['id']; ?>]" value="A"> <?php echo $question['OptionA']; ?></label></p>
<p><label><input type="radio" name="answer[<?php echo $question['id']; ?>]" value="B"> <?php echo $question['OptionB']; ?></label></p>
<p><label><input type="radio" name="answer[<?php echo $question['id']; ?>]" value="C"> <?php echo $question['OptionC']; ?></label></p>
<p><label><input type="radio" name="answer[<?php echo $question['id']; ?>]" value="D"> <?php echo $question['OptionD']; ?></label></p>
<?php } ?>
<br>
<input type="submit" name="submit_quiz" value="Submit Quiz">
</form>

<?php } ?>

</body>
</html>
<?php
if (isset($_POST['submit_quiz'])) {
    //Initialize variables to store the quiz score, total number of questions, and arrays of wrong and unanswered questions
    $score = 0;
    $total_questions = count($_POST['question_id']);
    $wrong_questions = array();
    $unanswered_questions = array();
    
    //Loop through each question in the quiz and check the user's answer against the correct answer stored in the database
foreach ($_POST['question_id'] as $key => $question_id) {
    //Retrieve the user's answer from the form and sanitize it to prevent SQL injection attacks
    $user_answer = isset($_POST['answer'][$key]) ? mysqli_real_escape_string($conn, $_POST['answer'][$key]) : null;
    
    //Build a query to retrieve the correct answer for the current question
    $answer_query = "SELECT Answer FROM questions WHERE id='$question_id'";
    
    //Execute the query and store the result in a variable
    $result = mysqli_query($conn, $answer_query);
    
    //Fetch the row from the query result and retrieve the correct answer from it
    $row = mysqli_fetch_assoc($result);
    $answer = $row['Answer'];
    
    //If the user's answer matches the correct answer, increment the score by 1
    if ($user_answer == $answer) {
        $score++;
    } 
    //If the user's answer is incorrect, add the question to the $wrong_questions array
    else {
        $question = mysqli_real_escape_string($conn, $_POST['question'][$key]);
        $option_a = mysqli_real_escape_string($conn, $_POST['option_a'][$key]);
        $wrong_questions[] = array(
            'id' => $question_id,
            'question' => isset($question) ? $question : null,
            'user_answer' => $user_answer,
            'correct_answer' => $answer,
            'option_a' => isset($option_a) ? $option_a : null
        );
    }
    
    //If any questions were retrieved from the database earlier, check if any of them were left unanswered
    if (isset($questions)) {
        foreach ($questions as $question) {
            if (!in_array($question['id'], $_POST['question_id'])) {
                //Retrieve the correct answer for the unanswered question
                $answer_query = "SELECT Answer FROM questions WHERE id='" . $question['id'] . "'";
                $result = mysqli_query($conn, $answer_query);
                $row = mysqli_fetch_assoc($result);
                $answer = $row['Answer'];
                
                //Add the unanswered question to the $unanswered_questions array
                $unanswered_questions[] = array(
                    'id' => $question['id'],
                    'question' => $question['Question'],
                    'option_a' => $question['OptionA'],
                    'correct_answer' => $answer
                );
            }
        }
    }
}
    
    //If any questions were retrieved from the database earlier, check if any of them were left unanswered
    if (isset($questions)) {
        foreach ($questions as $question) {
            if (!in_array($question['id'], $_POST['question_id'])) {
                $unanswered_questions[] = array(
                    'id' => $question['id'],
                    'question'=> $question['Question'],
                    'option_a' => $question['OptionA'],
                    'correct_answer' => $answer
);
}
}
}
$user_id = $_SESSION['username'];
$topic2=$_SESSION['topic'];
$difficulty2=$_SESSION['difficulty'];
    $score = $score . "/" . $total_questions;
    $sql = "INSERT INTO results (username, score, date_time, topic, difficulty) VALUES ('$user_id', '$score', NOW(), '$topic2', '$difficulty2')";
    if (mysqli_query($conn, $sql)) {
        echo "&nbsp&nbspQuiz Completed";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    echo "<a href='results.php'>View Results</a>";
}
?>
