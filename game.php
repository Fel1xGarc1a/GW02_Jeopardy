<?php
session_start();

// Handle the Reset Game request
if (isset($_POST['reset'])) {
    // Clear the session data
    session_unset();
    session_destroy();

    // Start a new session
    session_start();

    // Redirect to the starting page of your game
    header("Location: intro.html"); // Redirect back to the game page
    exit;
}
include 'questions.php';

// Function to display the game board
function displayBoard($questions) {
    // Find the maximum number of questions in any category
    $maxQuestions = 0;
    foreach ($questions as $category => $items) {
        $maxQuestions = max($maxQuestions, count($items));
    }

    echo '<table>';

    // Create the header row with categories
    echo '<tr>';
    foreach ($questions as $category => $items) {
        echo '<th>' . $category . '</th>';
    }
    echo '</tr>';

    // Create rows for questions
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
                // Empty cell if no question
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
// Function to display the question
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

// Initialize the game
if (!isset($_SESSION['playerCount']) && isset($_POST['playerCount'])) {
    $_SESSION['playerCount'] = $_POST['playerCount'];
    $_SESSION['currentPlayer'] = 1; // Initialize the current player to 1
    $_SESSION['scores'] = array_fill(1, $_SESSION['playerCount'], 0);
    $_SESSION['answered'] = array();
}

// Check if a question was selected
if (isset($_GET['category']) && isset($_GET['value'])) {
    $category = $_GET['category'];
    $value = $_GET['value'];

    // Find and display the question
    foreach ($questions[$category] as $item) {
        if ($item['value'] == $value) {
            displayQuestion($item['question'], $category, $value);
            break;
        }
    }
} elseif (isset($_POST['category'], $_POST['value'], $_POST['answer'])) {
    // Handle the answer submission
$category = $_POST['category'];
$value = $_POST['value'];
$playerAnswer = $_POST['answer'];

// Initialize a flag to track whether the answer is correct
$correctAnswerFound = false;

// Check if the answer is correct
foreach ($questions[$category] as $item) {
    if ($item['value'] == $value && strtolower($playerAnswer) == strtolower($item['answer'])) {
        // Update the score for the current player
        $_SESSION['scores'][$_SESSION['currentPlayer']] += $value;
        $correctAnswerFound = true; // Set the flag to true
        break; // Exit the loop
    }
}

// If the correct answer was not found, subtract points
if (!$correctAnswerFound) {
    $_SESSION['scores'][$_SESSION['currentPlayer']] -= $value;
}
    

    // Switch to the next player
    $_SESSION['currentPlayer'] = ($_SESSION['currentPlayer'] % $_SESSION['playerCount']) + 1;

    // Mark the question as answered
    $_SESSION['answered'][$category][$value] = true;

    // Redirect back to the board
    header("Location: game.php");
    exit;
} else {
    // Display the game board
    displayBoard($questions);
}
//Displaye Players turn
echo '<div class="PlayerTurn">';
echo "Player " . $_SESSION['currentPlayer'] . "'s Turn";
echo '</div>';
// Display scores
echo '<div class="scoreboard">';
for ($i = 1; $i <= $_SESSION['playerCount']; $i++) {
    echo "<p>Player $i Score: $" . $_SESSION['scores'][$i] . '</p>';
}
echo '</div>';

// Add the Reset Game button
echo '<div class="reset-game">';
echo '<form action="game.php" method="post">';
echo '<input type="submit" name="reset" value="Reset Game">';
echo '</form>';
echo '</div>';

?>
</body>
</html>
