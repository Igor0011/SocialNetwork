<?php

// session_start();
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
//     header("Location: index.php");
//     exit();
// }

require '../../inc/session.php';
require '../../engine/sql.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Basic Doodle Jump HTML Game</title>
    <meta charset="UTF-8">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        canvas {
            border: 1px solid black;
        }
    </style>
    <link rel="stylesheet" href="../../css/score.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php
    // Include the database connection file
    // include('db_connect.php');

    // Initialize the variable for the highest score
    $highestScore = 0;

    try {
        // SQL query to get the highest score
        $sql = "SELECT MAX(Score) AS HighestScore FROM GameScore WHERE game=2";

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
    <canvas width="375" height="667" class="gameCanvas" id="game"></canvas>
    <script>
        function getRandomInt(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        const canvas = document.getElementById('game');
        const context = canvas.getContext('2d');

        // width and height of each platform and where platforms start
        const platformWidth = 65;
        const platformHeight = 20;
        const platformStart = canvas.height - 50;

        // player physics
        const gravity = 0.33;
        const drag = 0.3;
        const bounceVelocity = -12.5;

        // minimum and maximum vertical space between each platform
        let minPlatformSpace = 15;
        let maxPlatformSpace = 20;

        // preload platform images
        const platformImages = [
            new Image(),
            new Image(),
            new Image()
        ];

        platformImages[0].src = '../../img/doge-platform.png';
        platformImages[1].src = '../../img/doge-platform.png';
        platformImages[2].src = '../../img/doge-platform.png'; // Replace with another image if needed

        // information about each platform. the first platform starts in the
        // bottom middle of the screen
        let platforms = [{
            x: canvas.width / 2 - platformWidth / 2,
            y: platformStart,
            image: platformImages[getRandomInt(0, platformImages.length - 1)],
            hasBeenLandedOn: false // Track if the platform has been landed on
        }];

        // initialize score
        let score = 0;

        // get a random number between the min (inclusive) and max (exclusive)
        function random(min, max) {
            return Math.random() * (max - min) + min;
        }

        // fill the initial screen with platforms
        let y = platformStart;
        while (y > 0) {
            y -= platformHeight + random(minPlatformSpace, maxPlatformSpace);

            let x;
            do {
                x = random(25, canvas.width - 25 - platformWidth);
            } while (
                y > canvas.height / 2 &&
                x > canvas.width / 2 - platformWidth * 1.5 &&
                x < canvas.width / 2 + platformWidth / 2
            );

            platforms.push({
                x: x,
                y: y,
                image: platformImages[getRandomInt(0, platformImages.length - 1)],
                hasBeenLandedOn: false // Track if the platform has been landed on
            });
        }

        // the doodle jumper
        const doodle = {
            width: 40,
            height: 60,
            x: canvas.width / 2 - 20,
            y: platformStart - 60,
            dx: 0,
            dy: 0
        };

        // load the image for the doodle
        const doodleImage = new Image();
        doodleImage.src = '../../img/doge-doodle.png'; // replace with the path to your doodle image

        // keep track of player direction and actions
        let playerDir = 0;
        let keydown = false;
        let prevDoodleY = doodle.y;

        // game loop
        function loop() {
            // game over logic
            if (doodle.y > canvas.height) {
                alert("Game Over! Final Score: " + score);
                var gameScores = [{
                        score: score,
                        game: 2,
                        gameName: "DoddleJump"
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
                return;
            }
            requestAnimationFrame(loop);
            context.clearRect(0, 0, canvas.width, canvas.height);

            // apply gravity to doodle
            doodle.dy += gravity;

            if (doodle.y < canvas.height / 2 && doodle.dy < 0) {
                platforms.forEach(function(platform) {
                    platform.y += -doodle.dy;
                });

                while (platforms[platforms.length - 1].y > 0) {
                    platforms.push({
                        x: random(25, canvas.width - 25 - platformWidth),
                        y: platforms[platforms.length - 1].y - (platformHeight + random(minPlatformSpace, maxPlatformSpace)),
                        image: platformImages[getRandomInt(0, platformImages.length - 1)],
                        hasBeenLandedOn: false // Track if the platform has been landed on
                    });

                    minPlatformSpace += 0.5;
                    maxPlatformSpace += 0.5;

                    maxPlatformSpace = Math.min(maxPlatformSpace, canvas.height / 2);
                }
            } else {
                doodle.y += doodle.dy;
            }

            if (!keydown) {
                if (playerDir < 0) {
                    doodle.dx += drag;
                    if (doodle.dx > 0) {
                        doodle.dx = 0;
                        playerDir = 0;
                    }
                } else if (playerDir > 0) {
                    doodle.dx -= drag;
                    if (doodle.dx < 0) {
                        doodle.dx = 0;
                        playerDir = 0;
                    }
                }
            }

            doodle.x += doodle.dx;

            if (doodle.x + doodle.width < 0) {
                doodle.x = canvas.width;
            } else if (doodle.x > canvas.width) {
                doodle.x = -doodle.width;
            }

            // draw platforms with image
            platforms.forEach(function(platform) {
                context.drawImage(platform.image, platform.x, platform.y, platformWidth, platformHeight);

                if (
                    doodle.dy > 0 &&
                    prevDoodleY + doodle.height <= platform.y &&
                    doodle.x < platform.x + platformWidth &&
                    doodle.x + doodle.width > platform.x &&
                    doodle.y < platform.y + platformHeight &&
                    doodle.y + doodle.height > platform.y
                ) {
                    // Only increase the score if the platform has not been landed on before
                    if (!platform.hasBeenLandedOn) {
                        doodle.y = platform.y - doodle.height;
                        doodle.dy = bounceVelocity;
                        platform.hasBeenLandedOn = true; // Mark platform as landed on
                        score += 10; // Adjust the score increment as needed
                    }
                }
            });

            // draw doodle with image
            context.drawImage(doodleImage, doodle.x, doodle.y, doodle.width, doodle.height);

            // draw score
            context.fillStyle = 'black';
            context.font = '24px Arial';
            context.fillText('Score: ' + score, 10, 30);

            prevDoodleY = doodle.y;

            // remove any platforms that have gone offscreen
            platforms = platforms.filter(function(platform) {
                return platform.y < canvas.height;
            });
        }

        // listen to keyboard events to move doodle
        document.addEventListener('keydown', function(e) {
            if (e.which === 37) {
                keydown = true;
                playerDir = -1;
                doodle.dx = -3;
            } else if (e.which === 39) {
                keydown = true;
                playerDir = 1;
                doodle.dx = 3;
            }
        });

        document.addEventListener('keyup', function(e) {
            keydown = false;
        });

        // start the game
        function startGame() {
            let loadedImagesCount = 0;
            const totalImages = platformImages.length + 1; // Include doodle image

            platformImages.forEach(function(img) {
                img.onload = function() {
                    loadedImagesCount++;
                    if (loadedImagesCount === totalImages) {
                        requestAnimationFrame(loop);
                    }
                };
            });

            doodleImage.onload = function() {
                loadedImagesCount++;
                if (loadedImagesCount === totalImages) {
                    requestAnimationFrame(loop);
                }
            };
        }

        startGame();
        console.log(123);
    </script>



</body>

</html>