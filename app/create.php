<?php
include("config.php");

//=====================================
// SAVE QUIZ
//=====================================

if(isset($_POST['save_quiz']))
{
    $quiz_name = trim($_POST['quiz_name']);

    if($quiz_name=="")
    {
        die("Quiz name is required.");
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO quizzes(quiz_name)
        VALUES(?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $quiz_name
    );

    mysqli_stmt_execute($stmt);

    $quiz_id = mysqli_insert_id($conn);

    $totalQuestions = count($_POST['question']);

    for($i=0;$i<$totalQuestions;$i++)
    {
        $question = trim($_POST['question'][$i]);
        $option1 = trim($_POST['option1'][$i]);
        $option2 = trim($_POST['option2'][$i]);
        $option3 = trim($_POST['option3'][$i]);
        $option4 = trim($_POST['option4'][$i]);
        $correct = intval($_POST['correct'][$i]);

        if(
            $question=="" ||
            $option1=="" ||
            $option2=="" ||
            $option3=="" ||
            $option4==""
        )
        {
            continue;
        }

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
            (
                ?,?,?,?,?,?,?
            )"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "isssssi",
            $quiz_id,
            $question,
            $option1,
            $option2,
            $option3,
            $option4,
            $correct
        );

        mysqli_stmt_execute($stmt);
    }

    header("Location:index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz App - Create Quiz</title>
    <!-- Tailwind CSS for sleek utility styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome for professional icons -->
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
        .correct-radio-label {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .option-container:focus-within {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .correct-selected {
            border-color: #10b981 !important;
            background-color: #f0fdf4 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="text-slate-800 min-h-screen pb-20">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-600 text-white p-2.5 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </div>
                    <div>
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">made with inis</span>
                        <h1 class="text-lg font-extrabold text-slate-950 tracking-tight leading-none">Quizapp</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <!-- Toggle Settings Switcher -->
                    <button type="button" onclick="toggleSidebar()" class="flex items-center space-x-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-4 py-2 rounded-xl transition duration-200 mr-1 text-sm">
                        <i id="headerToggleIcon" class="fa-solid fa-eye-slash text-xs text-indigo-600"></i>
                        <span id="headerToggleText" class="hidden sm:inline">Hide Settings</span>
                    </button>
                    <!-- Live Preview Action -->
                    <button type="button" onclick="openPreviewModal()" class="flex items-center space-x-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold px-4 py-2 rounded-xl transition duration-200 text-sm">
                        <i class="fa-solid fa-play text-xs text-emerald-600"></i>
                        <span class="hidden sm:inline">Live Preview</span>
                        <span class="sm:hidden">Preview</span>
                    </button>
                    <a href="index.php" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition py-2 px-3">Cancel</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <form id="quizForm" method="POST" class="space-y-8">
            
            <div class="flex flex-col lg:flex-row items-start relative">
                
                <!-- Collapsible Sidebar Panel -->
                <div id="settingsSidebar" class="w-full lg:w-80 lg:mr-8 shrink-0 mb-6 lg:mb-0 transition-all duration-300 ease-in-out overflow-hidden">
                    <div class="w-full lg:w-80 shrink-0">
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm lg:sticky lg:top-24">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <span class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-sm"><i class="fa-solid fa-sliders"></i></span>
                                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Quiz Settings</h3>
                                </div>
                                <button type="button" onclick="toggleSidebar()" class="text-slate-400 hover:text-slate-600 lg:hidden text-sm font-semibold">
                                    <i class="fa-solid fa-chevron-left mr-1"></i>Collapse
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Quiz Title</label>
                                    <input 
                                        type="text" 
                                        name="quiz_name" 
                                        id="quiz_name_input"
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-medium text-sm" 
                                        placeholder="e.g. Introduction to PHP Basics" 
                                        required>
                                </div>

                                <hr class="border-slate-100 my-4">

                                <!-- Dynamic Progress/Count Metric cards -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 block mb-0.5">Questions</span>
                                        <span id="questionCount" class="text-2xl font-black text-indigo-600">1</span>
                                    </div>
                                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                                        <span class="text-xs font-semibold text-slate-400 block mb-0.5">Est. Time</span>
                                        <span id="estTime" class="text-2xl font-black text-emerald-600">1 min</span>
                                    </div>
                                </div>

                                <div class="mt-4 bg-slate-50 rounded-xl p-3 border border-slate-100">
                                    <div class="flex items-center space-x-2.5 text-xs text-slate-500">
                                        <i class="fa-solid fa-circle-info text-indigo-500"></i>
                                        <span>Pick one correct answer for each question using the emerald indicator checkbox inside options.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Toggle Sidebar Handle Button (Visible on desktop to quickly slide/hide settings) -->
                <button type="button" id="sideCollapseToggleBtn" onclick="toggleSidebar()" class="hidden lg:flex items-center justify-center absolute left-[320px] -ml-4 top-24 z-10 bg-white hover:bg-slate-50 border border-slate-200 text-slate-400 hover:text-slate-700 w-8 h-8 rounded-full shadow-md transition-all duration-300 hover:scale-105" title="Toggle Settings Sidebar">
                    <i id="sideToggleIcon" class="fa-solid fa-chevron-left text-xs transition-transform duration-300"></i>
                </button>

                <div id="workspaceContainer" class="flex-1 w-full space-y-6">
                    
                    <div id="questions-container" class="space-y-6">
                        <!-- Card: Question 1 -->
                        <div class="question-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md animate-fade-in" data-index="0">
                            
                            <!-- Question Header -->
                            <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex justify-between items-center">
                                <div class="flex items-center space-x-3">
                                    <span class="question-number-badge bg-indigo-100 text-indigo-700 text-xs font-black px-2.5 py-1 rounded-lg">QUESTION 1</span>
                                    <span class="text-xs text-slate-400 font-medium italic saved-indicator">Drafting</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" onclick="duplicateQuestion(0)" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-200 transition" title="Duplicate Question">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                    <button type="button" onclick="removeQuestion(0)" class="delete-btn text-slate-300 hover:text-rose-500 p-1.5 rounded-lg hover:bg-rose-50/50 transition duration-200 hidden" title="Delete Question">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Question Card Body -->
                            <div class="p-6 space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Question Text</label>
                                    <textarea 
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-medium text-sm" 
                                        name="question[]" 
                                        rows="2" 
                                        placeholder="What would you like to ask?" 
                                        required></textarea>
                                </div>

                                <!-- Options Container -->
                                <div class="space-y-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Answer Options & Correct Key</label>
                                    
                                    <!-- Option 1 -->
                                    <div class="option-container flex items-center border border-slate-200 rounded-xl transition-all duration-200 overflow-hidden" data-val="1">
                                        <div class="px-4 py-3.5 bg-slate-50 border-r border-slate-200 flex items-center justify-center">
                                            <label class="relative flex items-center cursor-pointer">
                                                <input 
                                                    class="sr-only peer correct-radio-input" 
                                                    type="radio" 
                                                    name="correct[0]" 
                                                    value="1" 
                                                    checked
                                                    onchange="highlightCorrectAnswer(this)">
                                                <div class="w-6 h-6 border-2 border-slate-300 rounded-full flex items-center justify-center peer-checked:border-emerald-500 peer-checked:bg-emerald-500 transition-all">
                                                    <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                                </div>
                                            </label>
                                        </div>
                                        <input 
                                            type="text" 
                                            name="option1[]" 
                                            class="w-full px-4 py-3 text-sm font-medium border-0 focus:outline-none placeholder-slate-400" 
                                            placeholder="First option..." 
                                            required>
                                    </div>

                                    <!-- Option 2 -->
                                    <div class="option-container flex items-center border border-slate-200 rounded-xl transition-all duration-200 overflow-hidden" data-val="2">
                                        <div class="px-4 py-3.5 bg-slate-50 border-r border-slate-200 flex items-center justify-center">
                                            <label class="relative flex items-center cursor-pointer">
                                                <input 
                                                    class="sr-only peer correct-radio-input" 
                                                    type="radio" 
                                                    name="correct[0]" 
                                                    value="2" 
                                                    onchange="highlightCorrectAnswer(this)">
                                                <div class="w-6 h-6 border-2 border-slate-300 rounded-full flex items-center justify-center peer-checked:border-emerald-500 peer-checked:bg-emerald-500 transition-all">
                                                    <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                                </div>
                                            </label>
                                        </div>
                                        <input 
                                            type="text" 
                                            name="option2[]" 
                                            class="w-full px-4 py-3 text-sm font-medium border-0 focus:outline-none placeholder-slate-400" 
                                            placeholder="Second option..." 
                                            required>
                                    </div>

                                    <!-- Option 3 -->
                                    <div class="option-container flex items-center border border-slate-200 rounded-xl transition-all duration-200 overflow-hidden" data-val="3">
                                        <div class="px-4 py-3.5 bg-slate-50 border-r border-slate-200 flex items-center justify-center">
                                            <label class="relative flex items-center cursor-pointer">
                                                <input 
                                                    class="sr-only peer correct-radio-input" 
                                                    type="radio" 
                                                    name="correct[0]" 
                                                    value="3" 
                                                    onchange="highlightCorrectAnswer(this)">
                                                <div class="w-6 h-6 border-2 border-slate-300 rounded-full flex items-center justify-center peer-checked:border-emerald-500 peer-checked:bg-emerald-500 transition-all">
                                                    <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                                </div>
                                            </label>
                                        </div>
                                        <input 
                                            type="text" 
                                            name="option3[]" 
                                            class="w-full px-4 py-3 text-sm font-medium border-0 focus:outline-none placeholder-slate-400" 
                                            placeholder="Third option..." 
                                            required>
                                    </div>

                                    <!-- Option 4 -->
                                    <div class="option-container flex items-center border border-slate-200 rounded-xl transition-all duration-200 overflow-hidden" data-val="4">
                                        <div class="px-4 py-3.5 bg-slate-50 border-r border-slate-200 flex items-center justify-center">
                                            <label class="relative flex items-center cursor-pointer">
                                                <input 
                                                    class="sr-only peer correct-radio-input" 
                                                    type="radio" 
                                                    name="correct[0]" 
                                                    value="4" 
                                                    onchange="highlightCorrectAnswer(this)">
                                                <div class="w-6 h-6 border-2 border-slate-300 rounded-full flex items-center justify-center peer-checked:border-emerald-500 peer-checked:bg-emerald-500 transition-all">
                                                    <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                                </div>
                                            </label>
                                        </div>
                                        <input 
                                            type="text" 
                                            name="option4[]" 
                                            class="w-full px-4 py-3 text-sm font-medium border-0 focus:outline-none placeholder-slate-400" 
                                            placeholder="Fourth option..." 
                                            required>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Workspace controls -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-slate-100 p-4 rounded-2xl border border-slate-200/60">
                        <button type="button" onclick="addNewQuestion()" class="w-full sm:w-auto flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3.5 rounded-xl shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition duration-200">
                            <i class="fa-solid fa-plus text-sm"></i>
                            <span>Add Question</span>
                        </button>

                        <div class="flex w-full sm:w-auto items-center gap-3">
                            <button type="submit" name="save_quiz" class="w-full sm:w-auto flex items-center justify-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-emerald-100 hover:shadow-emerald-200 transition duration-200">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <span>Save Quiz Workspace</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </main>

    <!-- Live Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="bg-indigo-950 text-white p-6 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <span class="p-2 bg-white/10 rounded-xl text-indigo-300"><i class="fa-solid fa-play"></i></span>
                    <div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-indigo-400">Interactive Simulator</span>
                        <h3 id="previewQuizTitle" class="text-base font-extrabold tracking-tight">Quiz Draft Preview</h3>
                    </div>
                </div>
                <button type="button" onclick="closePreviewModal()" class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <!-- Body Content -->
            <div id="previewContentArea" class="p-6 overflow-y-auto space-y-6 flex-1">
                <!-- JavaScript will populate active questions interactive preview simulation -->
            </div>
            <!-- Actions -->
            <div class="bg-slate-50 border-t border-slate-100 px-6 py-4 flex justify-between items-center">
                <span id="previewProgressBadge" class="text-xs font-semibold text-slate-500">Q 1/1</span>
                <div class="flex space-x-2">
                    <button id="prevBtn" type="button" onclick="navigatePreview(-1)" class="px-4 py-2 border border-slate-200 hover:bg-slate-100 text-slate-600 rounded-xl text-sm font-semibold transition flex items-center space-x-2">
                        <i class="fa-solid fa-arrow-left"></i> <span>Back</span>
                    </button>
                    <button id="nextBtn" type="button" onclick="navigatePreview(1)" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition flex items-center space-x-2">
                        <span>Next</span> <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <button id="finishBtn" type="button" onclick="finishPreviewScore()" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition hidden flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check"></i> <span>Finish Simulation</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for adding dynamic new questions -->
    <div id="question-template" class="hidden">
        <div class="question-card bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md animate-fade-in" data-index="__INDEX__">
            <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <span class="question-number-badge bg-indigo-100 text-indigo-700 text-xs font-black px-2.5 py-1 rounded-lg">QUESTION __NUMBER__</span>
                    <span class="text-xs text-slate-400 font-medium italic saved-indicator">Drafting</span>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="duplicateQuestion(__INDEX__)" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-200 transition" title="Duplicate Question">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                    <button type="button" onclick="removeQuestion(__INDEX__)" class="delete-btn text-slate-300 hover:text-rose-500 p-1.5 rounded-lg hover:bg-rose-50/50 transition duration-200" title="Delete Question">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Question Text</label>
                    <textarea 
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all font-medium text-sm" 
                        name="question[]" 
                        rows="2" 
                        placeholder="What would you like to ask?" 
                        required></textarea>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Answer Options & Correct Key</label>
                    
                    <div class="option-container flex items-center border border-slate-200 rounded-xl transition-all duration-200 overflow-hidden" data-val="1">
                        <div class="px-4 py-3.5 bg-slate-50 border-r border-slate-200 flex items-center justify-center">
                            <label class="relative flex items-center cursor-pointer">
                                <input 
                                    class="sr-only peer correct-radio-input" 
                                    type="radio" 
                                    name="correct[__INDEX__]" 
                                    value="1" 
                                    checked
                                    onchange="highlightCorrectAnswer(this)">
                                <div class="w-6 h-6 border-2 border-slate-300 rounded-full flex items-center justify-center peer-checked:border-emerald-500 peer-checked:bg-emerald-500 transition-all">
                                    <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                            </label>
                        </div>
                        <input 
                            type="text" 
                            name="option1[]" 
                            class="w-full px-4 py-3 text-sm font-medium border-0 focus:outline-none placeholder-slate-400" 
                            placeholder="First option..." 
                            required>
                    </div>

                    <div class="option-container flex items-center border border-slate-200 rounded-xl transition-all duration-200 overflow-hidden" data-val="2">
                        <div class="px-4 py-3.5 bg-slate-50 border-r border-slate-200 flex items-center justify-center">
                            <label class="relative flex items-center cursor-pointer">
                                <input 
                                    class="sr-only peer correct-radio-input" 
                                    type="radio" 
                                    name="correct[__INDEX__]" 
                                    value="2" 
                                    onchange="highlightCorrectAnswer(this)">
                                <div class="w-6 h-6 border-2 border-slate-300 rounded-full flex items-center justify-center peer-checked:border-emerald-500 peer-checked:bg-emerald-500 transition-all">
                                    <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                            </label>
                        </div>
                        <input 
                            type="text" 
                            name="option2[]" 
                            class="w-full px-4 py-3 text-sm font-medium border-0 focus:outline-none placeholder-slate-400" 
                            placeholder="Second option..." 
                            required>
                    </div>

                    <div class="option-container flex items-center border border-slate-200 rounded-xl transition-all duration-200 overflow-hidden" data-val="3">
                        <div class="px-4 py-3.5 bg-slate-50 border-r border-slate-200 flex items-center justify-center">
                            <label class="relative flex items-center cursor-pointer">
                                <input 
                                    class="sr-only peer correct-radio-input" 
                                    type="radio" 
                                    name="correct[__INDEX__]" 
                                    value="3" 
                                    onchange="highlightCorrectAnswer(this)">
                                <div class="w-6 h-6 border-2 border-slate-300 rounded-full flex items-center justify-center peer-checked:border-emerald-500 peer-checked:bg-emerald-500 transition-all">
                                    <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                            </label>
                        </div>
                        <input 
                            type="text" 
                            name="option3[]" 
                            class="w-full px-4 py-3 text-sm font-medium border-0 focus:outline-none placeholder-slate-400" 
                            placeholder="Third option..." 
                            required>
                    </div>

                    <div class="option-container flex items-center border border-slate-200 rounded-xl transition-all duration-200 overflow-hidden" data-val="4">
                        <div class="px-4 py-3.5 bg-slate-50 border-r border-slate-200 flex items-center justify-center">
                            <label class="relative flex items-center cursor-pointer">
                                <input 
                                    class="sr-only peer correct-radio-input" 
                                    type="radio" 
                                    name="correct[__INDEX__]" 
                                    value="4" 
                                    onchange="highlightCorrectAnswer(this)">
                                <div class="w-6 h-6 border-2 border-slate-300 rounded-full flex items-center justify-center peer-checked:border-emerald-500 peer-checked:bg-emerald-500 transition-all">
                                    <i class="fa-solid fa-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                </div>
                            </label>
                        </div>
                        <input 
                            type="text" 
                            name="option4[]" 
                            class="w-full px-4 py-3 text-sm font-medium border-0 focus:outline-none placeholder-slate-400" 
                            placeholder="Fourth option..." 
                            required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Collapsible Sidebar Management
        function toggleSidebar() {
            const sidebar = document.getElementById('settingsSidebar');
            const button = document.getElementById('sideCollapseToggleBtn');
            const sideToggleIcon = document.getElementById('sideToggleIcon');
            const headerToggleIcon = document.getElementById('headerToggleIcon');
            const headerToggleText = document.getElementById('headerToggleText');
            
            // Check if settings sidebar is currently collapsed
            const isCollapsed = sidebar.classList.contains('lg:w-0');
            
            if (isCollapsed) {
                // EXPAND settings block
                sidebar.classList.remove('lg:w-0', 'lg:mr-0', 'w-0', 'mb-0', 'opacity-0', 'pointer-events-none');
                sidebar.classList.add('lg:w-80', 'lg:mr-8', 'w-full', 'mb-6');
                
                // Slide the toggle coordinate back to expand width positions
                button.style.left = '320px';
                sideToggleIcon.classList.remove('rotate-180');
                
                if (headerToggleIcon) {
                    headerToggleIcon.classList.remove('fa-eye');
                    headerToggleIcon.classList.add('fa-eye-slash');
                }
                if (headerToggleText) headerToggleText.textContent = "Hide Settings";
            } else {
                // COLLAPSE settings block (to the left)
                sidebar.classList.add('lg:w-0', 'lg:mr-0', 'w-0', 'mb-0', 'opacity-0', 'pointer-events-none');
                sidebar.classList.remove('lg:w-80', 'lg:mr-8', 'w-full', 'mb-6');
                
                // Slide the toggle handle right next to the left coordinate zero margin
                button.style.left = '0px';
                sideToggleIcon.classList.add('rotate-180');
                
                if (headerToggleIcon) {
                    headerToggleIcon.classList.remove('fa-eye-slash');
                    headerToggleIcon.classList.add('fa-eye');
                }
                if (headerToggleText) headerToggleText.textContent = "Show Settings";
            }
        }

        // Auto highlights correct initial chosen option
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".correct-radio-input:checked").forEach(input => {
                highlightCorrectAnswer(input);
            });
            updateWorkspaceMetadata();
        });

        // Highlights selected options with visual emerald containers
        function highlightCorrectAnswer(radioElement) {
            const container = radioElement.closest(".option-container");
            if (!container) return;
            
            // Uncheck other options in same question scope
            const parentBlock = container.closest(".space-y-3");
            parentBlock.querySelectorAll(".option-container").forEach(opt => {
                opt.classList.remove("correct-selected");
            });

            // Highlight checked options
            if (radioElement.checked) {
                container.classList.add("correct-selected");
            }
        }

        // Appends a pristine new blank question component
        function addNewQuestion() {
            const container = document.getElementById('questions-container');
            const totalQuestions = container.querySelectorAll('.question-card').length;
            
            let templateHTML = document.getElementById('question-template').innerHTML;
            templateHTML = templateHTML.replaceAll('__INDEX__', totalQuestions);
            templateHTML = templateHTML.replaceAll('__NUMBER__', totalQuestions + 1);
            
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = templateHTML;
            const newCard = tempDiv.firstElementChild;
            
            container.appendChild(newCard);
            reindexAllQuestions();
            updateWorkspaceMetadata();
            
            // Auto scroll to newly added question card
            newCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        // Duplicates complete question structure including answers
        function duplicateQuestion(index) {
            const container = document.getElementById('questions-container');
            const questionCards = container.querySelectorAll('.question-card');
            const targetCard = Array.from(questionCards).find(card => parseInt(card.getAttribute('data-index')) === index);
            
            if (!targetCard) return;

            const clonedCard = targetCard.cloneNode(true);
            
            // Capture selected values because cloneNode doesn't clone form state
            const targetRadioChecked = targetCard.querySelector('.correct-radio-input:checked');
            const originalCorrectVal = targetRadioChecked ? targetRadioChecked.value : "1";
            
            // Reset and assign clone fields values
            clonedCard.querySelector('textarea[name="question[]"]').value = targetCard.querySelector('textarea[name="question[]"]').value;
            clonedCard.querySelector('input[name="option1[]"]').value = targetCard.querySelector('input[name="option1[]"]').value;
            clonedCard.querySelector('input[name="option2[]"]').value = targetCard.querySelector('input[name="option2[]"]').value;
            clonedCard.querySelector('input[name="option3[]"]').value = targetCard.querySelector('input[name="option3[]"]').value;
            clonedCard.querySelector('input[name="option4[]"]').value = targetCard.querySelector('input[name="option4[]"]').value;

            // Insert after target element
            targetCard.insertAdjacentElement('afterend', clonedCard);
            
            reindexAllQuestions();
            
            // Reapply radio checks after indices have updated correctly
            const updatedCards = container.querySelectorAll('.question-card');
            updatedCards.forEach((card, idx) => {
                if (card === clonedCard) {
                    const clonedRadios = clonedCard.querySelectorAll('.correct-radio-input');
                    clonedRadios.forEach(radio => {
                        if (radio.value === originalCorrectVal) {
                            radio.checked = true;
                            highlightCorrectAnswer(radio);
                        }
                    });
                }
            });

            updateWorkspaceMetadata();
        }

        // Removes question card with safety checks
        function removeQuestion(index) {
            const container = document.getElementById('questions-container');
            const questionCards = container.querySelectorAll('.question-card');
            
            if (questionCards.length <= 1) {
                alert("Quiz must have at least one valid question.");
                return;
            }

            const targetCard = Array.from(questionCards).find(card => parseInt(card.getAttribute('data-index')) === index);
            if (targetCard) {
                targetCard.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    targetCard.remove();
                    reindexAllQuestions();
                    updateWorkspaceMetadata();
                }, 200);
            }
        }

        // Sync index counts and input form radio mappings
        function reindexAllQuestions() {
            const container = document.getElementById('questions-container');
            const cards = container.querySelectorAll('.question-card');
            
            cards.forEach((card, idx) => {
                card.setAttribute('data-index', idx);
                
                // Update Badge text
                const badge = card.querySelector('.question-number-badge');
                if (badge) badge.textContent = `QUESTION ${idx + 1}`;
                
                // Update Radio name groupings to prevent crossing questions
                const radioInputs = card.querySelectorAll('.correct-radio-input');
                radioInputs.forEach(input => {
                    input.name = `correct[${idx}]`;
                });

                // Update controls parameters
                const duplicateBtn = card.querySelector('button[onclick^="duplicateQuestion"]');
                if (duplicateBtn) duplicateBtn.setAttribute('onclick', `duplicateQuestion(${idx})`);
                
                const deleteBtn = card.querySelector('button[onclick^="removeQuestion"]');
                if (deleteBtn) {
                    deleteBtn.setAttribute('onclick', `removeQuestion(${idx})`);
                    // Always show delete options except for the first single card
                    if (cards.length > 1) {
                        deleteBtn.classList.remove('hidden');
                    } else {
                        deleteBtn.classList.add('hidden');
                    }
                }
            });
        }

        // Auto updates live metrics on settings block panel
        function updateWorkspaceMetadata() {
            const count = document.getElementById('questions-container').querySelectorAll('.question-card').length;
            document.getElementById('questionCount').textContent = count;
            
            // Simple rule of thumb: 1.5 minutes per question
            const durationSec = Math.round(count * 1.5);
            document.getElementById('estTime').textContent = `${durationSec} min`;
        }

        // Interactive live simulator preview logic
        let previewActiveIndex = 0;
        let previewUserAnswers = {};
        let draftQuizData = [];

        function openPreviewModal() {
            const modal = document.getElementById('previewModal');
            const titleInput = document.getElementById('quiz_name_input');
            const modalTitleText = document.getElementById('previewQuizTitle');
            
            modalTitleText.textContent = titleInput.value.trim() !== "" ? titleInput.value.trim() : "Untitled Quiz Simulation";
            
            // Map live questions card data inputs to temp memory array
            draftQuizData = [];
            previewUserAnswers = {};
            const cards = document.querySelectorAll('#questions-container .question-card');
            
            cards.forEach((card, index) => {
                const questionText = card.querySelector('textarea[name="question[]"]').value.trim();
                const o1 = card.querySelector('input[name="option1[]"]').value.trim();
                const o2 = card.querySelector('input[name="option2[]"]').value.trim();
                const o3 = card.querySelector('input[name="option3[]"]').value.trim();
                const o4 = card.querySelector('input[name="option4[]"]').value.trim();
                const correctVal = card.querySelector('.correct-radio-input:checked').value;
                
                draftQuizData.push({
                    text: questionText || `[Drafting Question ${index + 1}]`,
                    o1: o1 || `Option A`,
                    o2: o2 || `Option B`,
                    o3: o3 || `Option C`,
                    o4: o4 || `Option D`,
                    correct: parseInt(correctVal)
                });
            });

            previewActiveIndex = 0;
            renderPreviewQuestionCard();
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 50);
        }

        function closePreviewModal() {
            const modal = document.getElementById('previewModal');
            modal.classList.add('opacity-0');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Dynamically renders the simulated active question preview card
        function renderPreviewQuestionCard() {
            const container = document.getElementById('previewContentArea');
            const progress = document.getElementById('previewProgressBadge');
            const prev = document.getElementById('prevBtn');
            const next = document.getElementById('nextBtn');
            const finish = document.getElementById('finishBtn');
            
            const questionCount = draftQuizData.length;
            const activeQuestion = draftQuizData[previewActiveIndex];
            
            // Adjust footer navigation visibility state
            progress.textContent = `Question ${previewActiveIndex + 1} of ${questionCount}`;
            prev.disabled = previewActiveIndex === 0;
            if (previewActiveIndex === 0) {
                prev.classList.add('opacity-40', 'pointer-events-none');
            } else {
                prev.classList.remove('opacity-40', 'pointer-events-none');
            }

            if (previewActiveIndex === questionCount - 1) {
                next.classList.add('hidden');
                finish.classList.remove('hidden');
            } else {
                next.classList.remove('hidden');
                finish.classList.add('hidden');
            }

            const chosenAnswer = previewUserAnswers[previewActiveIndex] || null;

            container.innerHTML = `
                <div class="space-y-4">
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg inline-block uppercase tracking-wide">Question ID ${previewActiveIndex + 1}</span>
                    <h4 class="text-lg font-bold text-slate-900 leading-snug">${activeQuestion.text}</h4>
                    <div class="space-y-2.5 mt-4">
                        ${renderOptionBtn(1, activeQuestion.o1, chosenAnswer)}
                        ${renderOptionBtn(2, activeQuestion.o2, chosenAnswer)}
                        ${renderOptionBtn(3, activeQuestion.o3, chosenAnswer)}
                        ${renderOptionBtn(4, activeQuestion.o4, chosenAnswer)}
                    </div>
                </div>
            `;
        }

        function renderOptionBtn(val, text, selectedVal) {
            const isChecked = selectedVal === val;
            const borderStyle = isChecked ? 'border-indigo-600 bg-indigo-50/50 ring-2 ring-indigo-500/10' : 'border-slate-200 hover:bg-slate-50';
            const circleStyle = isChecked ? 'border-indigo-600 bg-indigo-600 text-white' : 'border-slate-300 text-transparent';
            
            return `
                <button type="button" onclick="selectPreviewOption(${val})" class="w-full text-left p-4 rounded-xl border flex items-center space-x-3 transition-all ${borderStyle}">
                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center transition-colors text-xs font-bold ${circleStyle}">
                        <i class="fa-solid fa-circle text-[7px]"></i>
                    </div>
                    <span class="text-sm font-medium text-slate-800">${text}</span>
                </button>
            `;
        }

        function selectPreviewOption(val) {
            previewUserAnswers[previewActiveIndex] = val;
            renderPreviewQuestionCard();
        }

        function navigatePreview(direction) {
            previewActiveIndex += direction;
            renderPreviewQuestionCard();
        }

        // Tabulates simulated final quiz results report page
        function finishPreviewScore() {
            const container = document.getElementById('previewContentArea');
            const progress = document.getElementById('previewProgressBadge');
            const prev = document.getElementById('prevBtn');
            const finish = document.getElementById('finishBtn');

            let score = 0;
            draftQuizData.forEach((q, index) => {
                if (previewUserAnswers[index] === q.correct) {
                    score++;
                }
            });

            const percent = draftQuizData.length > 0 ? Math.round((score / draftQuizData.length) * 100) : 0;
            let feedbackColor = 'text-indigo-600';
            let feedbackBg = 'bg-indigo-50';
            let ratingMessage = "Excellent draft work! Prepare to publish your quiz!";

            if (percent < 50) {
                feedbackColor = 'text-rose-600';
                feedbackBg = 'bg-rose-50';
                ratingMessage = "Take note of key solutions before sharing.";
            }

            container.innerHTML = `
                <div class="text-center py-8 space-y-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full ${feedbackBg} ${feedbackColor} text-4xl mb-2">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-slate-900">Preview Completed</h4>
                        <p class="text-sm text-slate-500 mt-1">Here is how users would experience this draft</p>
                    </div>

                    <div class="max-w-xs mx-auto border border-slate-100 rounded-2xl bg-slate-50 p-6 shadow-sm">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-widest block mb-1">Score Result</span>
                        <div class="text-4xl font-black ${feedbackColor}">${score} / ${draftQuizData.length}</div>
                        <div class="text-xs font-bold text-slate-400 mt-2">Accuracy Percentage: ${percent}%</div>
                    </div>

                    <p class="text-sm text-slate-600 max-w-sm mx-auto font-medium">${ratingMessage}</p>

                    <button type="button" onclick="openPreviewModal()" class="inline-flex items-center space-x-2 text-indigo-600 hover:text-indigo-800 text-xs font-bold uppercase tracking-wider transition">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Restart Preview Simulation</span>
                    </button>
                </div>
            `;

            progress.textContent = "Simulation finished";
            prev.disabled = true;
            prev.classList.add('opacity-40', 'pointer-events-none');
            finish.classList.add('hidden');
        }
    </script>
</body>
</html>