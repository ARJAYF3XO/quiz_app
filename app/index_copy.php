<?php
include("config.php");

// Delete Quiz
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = mysqli_prepare($conn, "DELETE FROM quizzes WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    header("Location: index.php");
    exit();
}

// Load Quizzes
$sql = "SELECT
quizzes.id,
quizzes.quiz_name,
COUNT(questions.id) AS total_questions

FROM quizzes

LEFT JOIN questions
ON quizzes.id = questions.quiz_id

GROUP BY quizzes.id

ORDER BY quizzes.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Quiz Application</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-lg-10">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h2 class="mb-0">Quiz Application</h2>

</div>

<div class="card-body">

<div class="d-flex justify-content-between mb-3">

<h4>Available Quizzes</h4>

<a href="create.php" class="btn btn-success">
Create Quiz
</a>

</div>

<?php

if(mysqli_num_rows($result)==0)
{
?>

<div class="alert alert-warning">

No quizzes have been created.

</div>

<?php
}
else
{
?>

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th width="10%">ID</th>

<th>Quiz Name</th>

<th width="15%">Questions</th>

<th width="25%">Actions</th>

</tr>

</thead>

<tbody>

<?php

while($quiz=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<?php echo $quiz['id']; ?>

</td>

<td>

<?php echo e($quiz['quiz_name']); ?>

</td>

<td>

<?php echo $quiz['total_questions']; ?>

</td>

<td>

<a
href="take.php?quiz=<?php echo $quiz['id']; ?>"
class="btn btn-primary btn-sm">

Take Quiz

</a>

<a
href="update.php?id=<?php echo $quiz['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="index.php?delete=<?php echo $quiz['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this quiz?')">

Delete

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

<?php

}

?>

</div>

</div>

</div>

</div>

</div>

</body>

</html>