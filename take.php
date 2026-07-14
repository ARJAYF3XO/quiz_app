<?php
include("config.php");

// Safe escape utility fallback to prevent runtime crashes
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if(!isset($_GET['quiz']))
{
    header("Location:index.php");
    exit();
}

$quiz_id = intval($_GET['quiz']);

//==============================
// LOAD QUIZ
//==============================

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM quizzes WHERE id=?"
);

mysqli_stmt_bind_param($stmt,"i",$quiz_id);
mysqli_stmt_execute($stmt);

$quiz = mysqli_fetch_assoc(
    mysqli_stmt_get_result($stmt)
);

if(!$quiz)
{
    die("Quiz not found.");
}

//==============================
// LOAD QUESTIONS
//==============================

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM questions
    WHERE quiz_id=?
    ORDER BY id ASC"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $quiz_id
);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$questions=[];
while($row=mysqli_fetch_assoc($result))
{
    $questions[]=$row;
}

$totalQuestions=count($questions);

if($totalQuestions==0)
{
    die("This quiz has no questions.");
}

//==============================
// SESSION
//==============================

if(
    !isset($_SESSION['quiz']) ||
    $_SESSION['quiz']!=$quiz_id
){
    $_SESSION['quiz']=$quiz_id;
    $_SESSION['current']=0;
    $_SESSION['score']=0;
    $_SESSION['answered']=false;
    $_SESSION['selected']=0;
}

$current=$_SESSION['current'];

if($current>=$totalQuestions)
{
    $current=$totalQuestions-1;
}

$question=$questions[$current];

//==============================
// SUBMIT ANSWER
//==============================

if(
    isset($_POST['submit'])
    &&
    !$_SESSION['answered']
){
    $selected=intval($_POST['answer']);
    $_SESSION['selected']=$selected;
    $_SESSION['answered']=true;

    if($selected==$question['correct_answer']){
        $_SESSION['score']++;
    }
}

//==============================
// NEXT QUESTION / FINISH HANDLER
//==============================

if(isset($_POST['next']))
{
    $_SESSION['current']++;
    $_SESSION['answered']=false;
    $_SESSION['selected']=0;

    if($_SESSION['current']>=$totalQuestions)
    {
        $percent = ($_SESSION['score'] / $totalQuestions) * 100;
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Quiz Finished - <?php echo e($quiz['quiz_name']); ?></title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
            <style>
                body {
                    font-family: 'Plus Jakarta Sans', sans-serif;
                    background-color: #f8fafc;
                }
            </style>
        </head>
        <body class="text-slate-800 min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-xl animate-fade-in">
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xl shadow-slate-100 overflow-hidden transform transition-all duration-300">
                    <!-- Finished Status Header -->
                    <div class="bg-indigo-950 text-white p-8 text-center relative overflow-hidden">
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-800/20 rounded-full blur-2xl"></div>
                        <div class="absolute -left-10 -top-10 w-40 h-40 bg-indigo-800/20 rounded-full blur-2xl"></div>
                        
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-400 text-3xl mb-4 border border-emerald-500/20">
                            <i class="fa-solid fa-trophy"></i>
                        </div>
                        <h2 class="text-2xl font-black tracking-tight">Quiz Completed!</h2>
                        <p class="text-indigo-200 text-sm mt-1 max-w-xs mx-auto"><?php echo e($quiz['quiz_name']); ?></p>
                    </div>

                    <!-- Report Body -->
                    <div class="p-8 text-center space-y-6">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block mb-2">Your Final Score</span>
                            <div class="inline-flex items-baseline space-x-1">
                                <span class="text-6xl font-black text-indigo-600 tracking-tight"><?php echo $_SESSION['score']; ?></span>
                                <span class="text-xl font-bold text-slate-400">/</span>
                                <span class="text-2xl font-extrabold text-slate-500"><?php echo $totalQuestions; ?></span>
                            </div>
                        </div>

                        <!-- Progress Bar Section -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-xs font-bold text-slate-500 uppercase tracking-wide">
                                <span>Accuracy Ring</span>
                                <span class="text-indigo-600"><?php echo round($percent); ?>%</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000 ease-out" style="width: <?php echo $percent; ?>%"></div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100">
                            <a href="index.php" class="inline-flex items-center justify-center space-x-2 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-4 rounded-xl shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition duration-200">
                                <i class="fa-solid fa-house text-sm"></i>
                                <span>Return Home</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        unset($_SESSION['quiz']);
        unset($_SESSION['current']);
        unset($_SESSION['score']);
        unset($_SESSION['answered']);
        unset($_SESSION['selected']);
        exit();
    }

    header("Location:take.php?quiz=".$quiz_id);
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz - <?php echo e($quiz['quiz_name']); ?></title>
    <!-- Tailwind CSS for sleek utility styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome for visual context indicators -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .option-card-interactive {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen pb-16 flex flex-col justify-between">

    <!-- Top Navigation Header -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-600 text-white p-2.5 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">EduPortal Live</span>
                        <h1 class="text-sm sm:text-base font-extrabold text-slate-950 tracking-tight leading-none truncate max-w-[180px] sm:max-w-xs">
                            <?php echo e($quiz['quiz_name']); ?>
                        </h1>
                    </div>
                </div>
                <div>
                    <a href="index.php" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition py-2 px-3 border border-slate-200 rounded-xl hover:bg-slate-50">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-1 text-slate-400"></i> Quit Quiz
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Quiz Panel Body -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 w-full mt-8 flex-1">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            
            <!-- Quiz Progress Tracker Banner -->
            <div class="bg-slate-50 border-b border-slate-100 px-6 sm:px-8 py-5 flex justify-between items-center flex-wrap gap-4">
                <div class="flex items-center space-x-3">
                    <span class="bg-indigo-100 text-indigo-700 text-xs font-black px-3 py-1.5 rounded-lg uppercase tracking-wide">
                        Question <?php echo $current + 1; ?> of <?php echo $totalQuestions; ?>
                    </span>
                    <!-- Progress Bar Track -->
                    <div class="hidden sm:block w-32 bg-slate-200 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-indigo-600 h-full rounded-full transition-all duration-300" style="width: <?php echo (($current + 1) / $totalQuestions) * 100; ?>%"></div>
                    </div>
                </div>

                <div class="flex items-center space-x-2 text-slate-500 font-bold text-sm bg-white border border-slate-100 px-3.5 py-1.5 rounded-xl">
                    <i class="fa-solid fa-star text-amber-500 text-xs"></i>
                    <span>Score: <strong class="text-slate-800 font-extrabold"><?php echo $_SESSION['score']; ?></strong></span>
                </div>
            </div>

            <!-- Quiz Content Workspace -->
            <div class="p-6 sm:p-8 space-y-6">
                <!-- Question Statement Box -->
                <div>
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5">Question Text</span>
                    <h4 class="text-lg sm:text-xl font-bold text-slate-900 leading-snug">
                        <?php echo e($question['question']); ?>
                    </h4>
                </div>

                <form method="POST" class="space-y-4">
                    <div class="space-y-3">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pick Your Choice</span>
                        
                        <?php
                        for($i=1; $i<=4; $i++)
                        {
                            $option = $question["option".$i];
                            
                            // Visual CSS classes depending on user checked states and solution match
                            $cardClasses = "option-card-interactive flex items-center p-4 rounded-xl border-2 transition-all duration-200 cursor-pointer w-full ";
                            $indicatorBorder = "border-slate-300";
                            $indicatorBg = "bg-transparent";
                            $indicatorContent = '<i class="fa-solid fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100 transition-opacity"></i>';

                            if($_SESSION['answered'])
                            {
                                if($i == $question['correct_answer'])
                                {
                                    // Highlight real correct options (Green)
                                    $cardClasses .= "border-emerald-500 bg-emerald-50 text-emerald-950 font-semibold cursor-not-allowed";
                                    $indicatorBorder = "border-emerald-600";
                                    $indicatorBg = "bg-emerald-600";
                                    $indicatorContent = '<i class="fa-solid fa-check text-white text-[10px]"></i>';
                                }
                                elseif($i == $_SESSION['selected'])
                                {
                                    // Highlight wrong options chosen by the participant (Red)
                                    $cardClasses .= "border-rose-400 bg-rose-50 text-rose-950 cursor-not-allowed";
                                    $indicatorBorder = "border-rose-500";
                                    $indicatorBg = "bg-rose-500";
                                    $indicatorContent = '<i class="fa-solid fa-xmark text-white text-[10px]"></i>';
                                }
                                else
                                {
                                    // Visual state of untouched choices when solution is open (Dull Gray)
                                    $cardClasses .= "border-slate-100 bg-slate-50/50 text-slate-400 cursor-not-allowed";
                                    $indicatorBorder = "border-slate-200";
                                }
                            }
                            else
                            {
                                // Highlight matching card color on custom selection prior to submission using parent matching (has-[:checked])
                                $cardClasses .= "border-slate-200 hover:border-indigo-500 hover:bg-slate-50 text-slate-800 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/50 has-[:checked]:text-indigo-950";
                            }
                            ?>
                            
                            <!-- Option Selection Card Block -->
                            <label class="<?php echo $cardClasses; ?>">
                                <input 
                                    type="radio" 
                                    name="answer" 
                                    value="<?php echo $i; ?>"
                                    class="sr-only peer"
                                    required
                                    <?php
                                    if($_SESSION['answered']) { echo "disabled"; }
                                    if($_SESSION['selected'] == $i) { echo " checked"; }
                                    ?>
                                >
                                
                                <!-- Decorative Radio Dot Wrapper -->
                                <div class="w-6 h-6 shrink-0 rounded-full border-2 flex items-center justify-center mr-4 transition-all <?php echo $indicatorBorder; ?> <?php echo $indicatorBg; ?> <?php if(!$_SESSION['answered']) { echo 'peer-checked:border-indigo-600 peer-checked:bg-indigo-600'; } ?>">
                                    <?php echo $indicatorContent; ?>
                                </div>

                                <span class="text-sm font-medium leading-normal"><?php echo e($option); ?></span>
                            </label>

                            <?php
                        }
                        ?>
                    </div>

                    <!-- Submit & Navigation Panel -->
                    <div class="pt-6 border-t border-slate-100">
                        <?php
                        if(!$_SESSION['answered'])
                        {
                            ?>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <button 
                                    type="submit" 
                                    name="submit" 
                                    class="w-full sm:w-auto flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition duration-200 cursor-pointer">
                                    <span>Submit Answer</span>
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                </button>
                                <a 
                                    href="index.php" 
                                    class="text-center font-bold text-slate-500 hover:text-slate-800 py-3 px-4 rounded-xl hover:bg-slate-50 transition">
                                    Quit and Return Home
                                </a>
                            </div>
                            <?php
                        }
                        else
                        {
                            if($_SESSION['selected'] == $question['correct_answer'])
                            {
                                ?>
                                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start space-x-3 text-emerald-950">
                                    <div class="p-1 bg-emerald-500 text-white rounded-lg text-xs shrink-0 mt-0.5"><i class="fa-solid fa-circle-check"></i></div>
                                    <div>
                                        <p class="font-bold text-sm">Correct answer!</p>
                                        <p class="text-xs text-emerald-800/80 mt-0.5">Awesome! Keep maintaining this streak across remaining cards.</p>
                                    </div>
                                </div>
                                <?php
                            }
                            else
                            {
                                ?>
                                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start space-x-3 text-rose-950">
                                    <div class="p-1 bg-rose-500 text-white rounded-lg text-xs shrink-0 mt-0.5"><i class="fa-solid fa-triangle-exclamation"></i></div>
                                    <div>
                                        <p class="font-bold text-sm">Incorrect response</p>
                                        <p class="text-xs text-rose-800/90 mt-1">Correct key answer was: <strong class="font-bold text-slate-900 bg-white px-2 py-0.5 rounded border border-slate-200"><?php echo e($question["option".$question['correct_answer']]); ?></strong></p>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <!-- Progress Progression Button -->
                            <div class="flex justify-end">
                                <button 
                                    type="submit" 
                                    name="next" 
                                    class="w-full sm:w-auto flex items-center justify-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-4 rounded-xl shadow-lg shadow-emerald-100 hover:shadow-emerald-200 transition duration-200 cursor-pointer">
                                    <span>
                                        <?php
                                        if($current + 1 == $totalQuestions) {
                                            echo "Finish Quiz";
                                        } else {
                                            echo "Next Question";
                                        }
                                        ?>
                                    </span>
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </button>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </form>

            </div>
        </div>
    </main>

    <!-- Tiny Brand Footer -->
    <footer class="mt-12 text-center text-xs text-slate-400 font-semibold uppercase tracking-widest">
        <span>Powered by Quiz Studio Engine &bull; EduPortal</span>
    </footer>

</body>
</html>