<?php
session_start();

if (isset($_POST['reset'])) {
    session_unset();
    session_destroy();
    session_start();

    header("Location: intro.html");
    exit;
}
include 'questions.php';

function displayBoard($questions) {
    $maxQuestions = 0;
    foreach ($questions as $category => $items) {
        $maxQuestions = max($maxQuestions, count($items));
    }

    echo '<table>';

    echo '<tr>';
    foreach ($questions as $category => $items) {
        echo '<th>' . $category . '</th>';
    }
    echo '</tr>';

    for ($i = 0; $i < $maxQuestions; $i++) {
        echo '<tr>';
        foreach ($questions as $category => $items) {
            if (isset($items[$i])) {
                $item = $items[$i];
                if (isset($_SESSION['answered'][$category][$item['value']])) {
                    echo '<td>---</td>';
                } else {
                    echo '<td><a href="?category=' . urlencode($category) . '&value=' . $item['value'] . '">$' . $item['value'] . '</a></td>';
                }
            } else {
                echo '<td></td>';
            }
        }
        echo '</tr>';
    }

    echo '</table>';
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Jeopardy Game</title>
</head>
<body>
<div class="header">
    <img src="logo.png" alt="Logo">
</div>
<?php
function displayQuestion($question, $category, $value) {
    echo "<div class='question'>";
    echo "<p>$question</p>";
    echo '<form action="game.php" method="post">';
    echo '<input type="hidden" name="category" value="' . $category . '">';
    echo '<input type="hidden" name="value" value="' . $value . '">';
    echo '<input type="text" name="answer" required>';
    echo '<input type="submit" value="Submit Answer">';
    echo '</form>';
    echo "</div>";
}

if (!isset($_SESSION['playerCount']) && isset($_POST['playerCount'])) {
    $_SESSION['playerCount'] = $_POST['playerCount'];
    $_SESSION['currentPlayer'] = 1;
    $_SESSION['scores'] = array_fill(1, $_SESSION['playerCount'], 0);
    $_SESSION['answered'] = array();
}

if (isset($_GET['category']) && isset($_GET['value'])) {
    $category = $_GET['category'];
    $value = $_GET['value'];

    foreach ($questions[$category] as $item) {
        if ($item['value'] == $value) {
            displayQuestion($item['question'], $category, $value);
            break;
        }
    }
} elseif (isset($_POST['category'], $_POST['value'], $_POST['answer'])) {
    if (isset($_POST['category'], $_POST['value'])) {
        $category = $_POST['category'];
        $value = $_POST['value'];
    }

    $playerAnswer = $_POST['answer'];

    $correctAnswerFound = false;
    $correctAnswer = "";

    foreach ($questions[$category] as $item) {
        if ($item['value'] == $value && strtolower($playerAnswer) == strtolower($item['answer'])) {
            $_SESSION['scores'][$_SESSION['currentPlayer']] += $value;
            $correctAnswerFound = true;
            break;
        } elseif ($item['value'] == $value) {
            $correctAnswer = $item['answer'];
        }
    }

    if (!$correctAnswerFound) {
        $_SESSION['scores'][$_SESSION['currentPlayer']] -= $value;
    }

    $_SESSION['currentPlayer'] = ($_SESSION['currentPlayer'] % $_SESSION['playerCount']) + 1;
    $_SESSION['answered'][$category][$value] = true;

    $answerMessage = "";
    if ($correctAnswerFound) {
        $answerMessage = "<p class='correct-answer'>Correct!</p>";
    } else {
        $answerMessage = "<p class='incorrect-answer'>Wrong! The correct answer is: $correctAnswer</p>";
    }

    header("Location: game.php?answerMessage=" . urlencode($answerMessage));
    exit;
} else {
    displayBoard($questions);
}

echo '<div class="PlayerTurn">';
if (isset($_GET['answerMessage'])) {
    $answerMessage = urldecode($_GET['answerMessage']);
    echo '<div class="answer-message">' . $answerMessage . '</div>';
}
echo '</div>';

echo '<div class="PlayerTurn">';
echo "Player " . $_SESSION['currentPlayer'] . "'s Turn";
echo '</div>';

echo '<div class="scoreboard">';
for ($i = 1; $i <= $_SESSION['playerCount']; $i++) {
    echo "<p>Player $i Score: $" . $_SESSION['scores'][$i] . '</p>';
}
echo '</div>';
echo '<div class="reset-game">';
echo '<form action="game.php" method="post">';
echo '<input type="submit" name="reset" value="Reset Game">';
echo '</form>';
echo '</div>';
?>
</body>
</html>
