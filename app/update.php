<?php

include("config.php");

//=======================================
// GET QUIZ ID
//=======================================

if(!isset($_GET['id']))
{
    header("Location:index.php");
    exit();
}

$quiz_id = intval($_GET['id']);

//=======================================
// SAVE CHANGES
//=======================================

if(isset($_POST['update_quiz']))
{

    $quiz_name = trim($_POST['quiz_name']);

    $stmt = mysqli_prepare(

        $conn,

        "UPDATE quizzes
        SET quiz_name=?
        WHERE id=?"

    );

    mysqli_stmt_bind_param(

        $stmt,

        "si",

        $quiz_name,

        $quiz_id

    );

    mysqli_stmt_execute($stmt);

    //====================================
    // UPDATE QUESTIONS
    //====================================

    $question_ids = $_POST['question_id'];

    $questions = $_POST['question'];

    $option1 = $_POST['option1'];

    $option2 = $_POST['option2'];

    $option3 = $_POST['option3'];

    $option4 = $_POST['option4'];

    $correct = $_POST['correct'];

    for($i=0;$i<count($questions);$i++)
    {

        $qid = intval($question_ids[$i]);

        if($qid>0)
        {

            $stmt = mysqli_prepare(

            $conn,

            "UPDATE questions

            SET

            question=?,

            option1=?,

            option2=?,

            option3=?,

            option4=?,

            correct_answer=?

            WHERE id=?"

            );

            mysqli_stmt_bind_param(

            $stmt,

            "sssssii",

            $questions[$i],

            $option1[$i],

            $option2[$i],

            $option3[$i],

            $option4[$i],

            $correct[$i],

            $qid

            );

            mysqli_stmt_execute($stmt);

        }
        else
        {

            $stmt = mysqli_prepare(

            $conn,

            "INSERT INTO questions
            (
            quiz_id,
            question,
            option1,
            option2,
            option3,
            option4,
            correct_answer
            )

            VALUES
            (?,?,?,?,?,?,?)"

            );

            mysqli_stmt_bind_param(

            $stmt,

            "isssssi",

            $quiz_id,

            $questions[$i],

            $option1[$i],

            $option2[$i],

            $option3[$i],

            $option4[$i],

            $correct[$i]

            );

            mysqli_stmt_execute($stmt);

        }

    }

    header("Location:index.php");

    exit();

}

//=======================================
// LOAD QUIZ
//=======================================

$stmt = mysqli_prepare(

$conn,

"SELECT * FROM quizzes
WHERE id=?"

);

mysqli_stmt_bind_param(

$stmt,

"i",

$quiz_id

);

mysqli_stmt_execute($stmt);

$quiz = mysqli_fetch_assoc(

mysqli_stmt_get_result($stmt)

);

if(!$quiz)
{

die("Quiz not found.");

}

//=======================================
// LOAD QUESTIONS
//=======================================

$stmt = mysqli_prepare(

$conn,

"SELECT *

FROM questions

WHERE quiz_id=?

ORDER BY id"

);

mysqli_stmt_bind_param(

$stmt,

"i",

$quiz_id

);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>

Update Quiz

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

.question-card{

margin-bottom:25px;

}

</style>

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-lg-10">

<div class="card shadow">

<div class="card-header bg-warning">

<h2>

Edit Quiz

</h2>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-4">

<label>

Quiz Name

</label>

<input

type="text"

name="quiz_name"

class="form-control"

value="<?php echo e($quiz['quiz_name']); ?>"

required>

</div>

<div id="questions">

<?php

$number=1;

while($row=mysqli_fetch_assoc($result))
{

?>

<div class="card question-card">

<div class="card-header">

Question

<?php

echo $number++;

?>

</div>

<div class="card-body">

<input

type="hidden"

name="question_id[]"

value="<?php echo $row['id']; ?>">

<div class="mb-3">

<label>

Question

</label>

<textarea

name="question[]"

class="form-control"

required><?php echo e($row['question']); ?></textarea>

</div>
<div class="mb-3">

<label>

Option 1

</label>

<div class="input-group">

<div class="input-group-text">

<input

class="form-check-input"

type="radio"

name="correct[<?php echo $number-2; ?>]"

value="1"

<?php

if($row['correct_answer']==1)
echo "checked";

?>

>

</div>

<input

type="text"

name="option1[]"

class="form-control"

value="<?php echo e($row['option1']); ?>"

required>

</div>

</div>

<div class="mb-3">

<label>

Option 2

</label>

<div class="input-group">

<div class="input-group-text">

<input

class="form-check-input"

type="radio"

name="correct[<?php echo $number-2; ?>]"

value="2"

<?php

if($row['correct_answer']==2)
echo "checked";

?>

>

</div>

<input

type="text"

name="option2[]"

class="form-control"

value="<?php echo e($row['option2']); ?>"

required>

</div>

</div>

<div class="mb-3">

<label>

Option 3

</label>

<div class="input-group">

<div class="input-group-text">

<input

class="form-check-input"

type="radio"

name="correct[<?php echo $number-2; ?>]"

value="3"

<?php

if($row['correct_answer']==3)
echo "checked";

?>

>

</div>

<input

type="text"

name="option3[]"

class="form-control"

value="<?php echo e($row['option3']); ?>"

required>

</div>

</div>

<div class="mb-4">

<label>

Option 4

</label>

<div class="input-group">

<div class="input-group-text">

<input

class="form-check-input"

type="radio"

name="correct[<?php echo $number-2; ?>]"

value="4"

<?php

if($row['correct_answer']==4)
echo "checked";

?>

>

</div>

<input

type="text"

name="option4[]"

class="form-control"

value="<?php echo e($row['option4']); ?>"

required>

</div>

</div>

<button

type="button"

class="btn btn-danger btn-sm remove-question">

Remove Question

</button>

</div>

</div>

<?php

}

?>

</div>

<div class="d-flex justify-content-between mt-3">

<button

type="button"

class="btn btn-secondary"

onclick="addQuestion()">

+ Add Question

</button>

<div>

<a

href="index.php"

class="btn btn-outline-secondary">

Cancel

</a>

<button

type="submit"

name="update_quiz"

class="btn btn-warning">

Save Changes

</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<script>

let questionNumber =
document.querySelectorAll(".question-card").length;

function addQuestion()
{

questionNumber++;

let index = questionNumber-1;

let html = `

<div class="card question-card">

<div class="card-header">

Question ${questionNumber}

</div>

<div class="card-body">

<input

type="hidden"

name="question_id[]"

value="0">

<div class="mb-3">

<label>

Question

</label>

<textarea

name="question[]"

class="form-control"

required

></textarea>

</div>

<div class="mb-3">

<label>

Option 1

</label>

<div class="input-group">

<div class="input-group-text">

<input

type="radio"

name="correct[${index}]"

value="1"

checked>

</div>

<input

type="text"

class="form-control"

name="option1[]"

required>

</div>

</div>
<div class="mb-3">

<label>

Option 2

</label>

<div class="input-group">

<div class="input-group-text">

<input

type="radio"

name="correct[${index}]"

value="2">

</div>

<input

type="text"

class="form-control"

name="option2[]"

required>

</div>

</div>

<div class="mb-3">

<label>

Option 3

</label>

<div class="input-group">

<div class="input-group-text">

<input

type="radio"

name="correct[${index}]"

value="3">

</div>

<input

type="text"

class="form-control"

name="option3[]"

required>

</div>

</div>

<div class="mb-4">

<label>

Option 4

</label>

<div class="input-group">

<div class="input-group-text">

<input

type="radio"

name="correct[${index}]"

value="4">

</div>

<input

type="text"

class="form-control"

name="option4[]"

required>

</div>

</div>

<button

type="button"

class="btn btn-danger btn-sm remove-question">

Remove Question

</button>

</div>

</div>

`;

document
.getElementById("questions")
.insertAdjacentHTML(
"beforeend",
html
);

attachRemoveButtons();

}

//==========================
// REMOVE QUESTION
//==========================

function attachRemoveButtons()
{

document
.querySelectorAll(".remove-question")
.forEach(function(button){

button.onclick=function(){

this
.closest(".question-card")
.remove();

};

});

}

attachRemoveButtons();

</script>

</body>

</html>