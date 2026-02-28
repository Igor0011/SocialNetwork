<?php
// session_start();
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header("Location: index.php");
//     exit();
// }
// print_r($_POST);
// 

require '../../inc/session.php';
require '../../engine/sql.php';
// echo $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snake Game</title>
    <link rel="stylesheet" href="../../css/explosion.css">
    <style>
        /* #gameCanvas {
            border: 1px solid black;
        } */

        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            /* Optional: Add a background color */
        }

        #gameCanvas {
            border: 1px solid black;
        }
    </style>
    <style>
        .score-container {
            padding: 20px;
            background-color: #f2f2f2;
            border-radius: 10px;
            font-family: Arial, sans-serif;
            text-align: center;
            width: 200px;
            margin: 50px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-right: 50px;
        }

        .score-container h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .score-container .score {
            font-size: 36px;
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<script>
    var loggedUserID = <?= $_SESSION['id'] ?>;
</script>

<body>
    <?php
    // Include the database connection file
    // include('db_connect.php');

    // Initialize the variable for the highest score
    $highestScore = 0;

    try {
        // SQL query to get the highest score
        $sql = "SELECT MAX(Score) AS HighestScore FROM GameScore WHERE game=1";

        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Get the highest score from the result
            $highestScore = $row['HighestScore'];
        }
    } catch (PDOException $e) {
        echo "Error fetching highest score: " . $e->getMessage();
        exit();
    }
    ?>

    <div class="score-container">
        <h2>Highest Score</h2>
        <div class="score"><?php echo $highestScore; ?></div>
    </div>
    <div id="explosion" class="explosion"></div>
    <!-- <h1>Snake Game</h1> -->
    <canvas id="gameCanvas" width="500" height="500"></canvas>
    <!-- <script src="game.js"></script> -->
</body>

</html>
<?php
if (isset($_POST['score'])) {
    $score = intval($_POST['score']);
    $file = 'highscore.txt';

    if (file_exists($file)) {
        $highscore = intval(file_get_contents($file));
    } else {
        $highscore = 0;
    }

    if ($score > $highscore) {
        file_put_contents($file, $score);
    }
}
?>

<script>
    function triggerExplosion() {
        const explosion = document.getElementById('explosion');

        // Reset animation by removing and adding the class
        explosion.style.display = 'block';
        explosion.classList.remove('explosion');
        void explosion.offsetWidth; // Trigger reflow to restart animation
        explosion.classList.add('explosion');

        // Add the animation
        explosion.style.animation = 'explode 1s ease-out, fadeOut 1s ease-in 1s forwards';

        // Hide the explosion after the animation ends
        explosion.addEventListener('animationend', () => {
            explosion.style.display = 'none'; // Hide the explosion after it finishes
        });
    }


    const canvas = document.getElementById('gameCanvas');
    const ctx = canvas.getContext('2d');
    const box = 20;
    let score = 0;

    let snake = [];
    snake[0] = {
        x: 9 * box,
        y: 10 * box
    };

    let food = {
        x: Math.floor(Math.random() * 25) * box,
        y: Math.floor(Math.random() * 25) * box
    };

    let d;
    document.addEventListener('keydown', direction);

    function direction(event) {
        if (event.keyCode == 37 && d != 'RIGHT') d = 'LEFT';
        else if (event.keyCode == 38 && d != 'DOWN') d = 'UP';
        else if (event.keyCode == 39 && d != 'LEFT') d = 'RIGHT';
        else if (event.keyCode == 40 && d != 'UP') d = 'DOWN';
    }

    function collision(head, array) {
        for (let i = 0; i < array.length; i++) {
            if (head.x === array[i].x && head.y === array[i].y) return true;
        }
        return false;
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        for (let i = 0; i < snake.length; i++) {
            ctx.fillStyle = i === 0 ? 'green' : 'white';
            ctx.fillRect(snake[i].x, snake[i].y, box, box);
            ctx.strokeStyle = 'black';
            ctx.strokeRect(snake[i].x, snake[i].y, box, box);
        }

        ctx.fillStyle = 'red';
        ctx.fillRect(food.x, food.y, box, box);

        let snakeX = snake[0].x;
        let snakeY = snake[0].y;

        if (d === 'LEFT') snakeX -= box;
        if (d === 'UP') snakeY -= box;
        if (d === 'RIGHT') snakeX += box;
        if (d === 'DOWN') snakeY += box;

        if (snakeX === food.x && snakeY === food.y) {
            score++;
            food = {
                x: Math.floor(Math.random() * 25) * box,
                y: Math.floor(Math.random() * 25) * box
            };
        } else {
            snake.pop();
        }

        const newHead = {
            x: snakeX,
            y: snakeY
        };

        if (snakeX < 0 || snakeX >= canvas.width || snakeY < 0 || snakeY >= canvas.height || collision(newHead, snake)) {
            clearInterval(game);
            console.log("crash");
            console.log(loggedUserID);
            setTimeout(function() {
                // console.log("This message is delayed by 2 seconds");
                triggerExplosion();
            }, 200);

            var gameScores = [{
                    score: score,
                    game: 1,
                    gameName: "Snake"
                },
                // {score: 200, game: 2, gameName: "Pac-Man"},
                // {score: 300, game: 3, gameName: "Tetris"}
            ];

            // AJAX request to send data to server
            $.ajax({
                url: '../../ajx/ajxInsertGameScore.php', // PHP script that handles the insert
                type: 'POST',
                data: {
                    gameScores: JSON.stringify(gameScores)
                }, // Send array as JSON string
                success: function(response) {
                    // alert('Game scores inserted successfully: ' + response);
                },
                error: function() {
                    // alert('Error occurred while inserting game scores.');
                }
            });
        }

        snake.unshift(newHead);

        ctx.fillStyle = 'black';
        ctx.font = '30px Arial';
        ctx.fillText('Score: ' + score, box, box);


    }

    const game = setInterval(draw, 100);
</script>