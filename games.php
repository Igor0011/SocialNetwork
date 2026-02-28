<?php require "inc/session.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title</title>
    <link rel="stylesheet" href="css/profile.css">

</head>

<body>
    <div class="wrapper">
        <?php require 'inc/navbar.php' ?>
        <main class="container">
            <div class="container">
                <div class="row">
                    <div class="card">
                        <img src="" alt="Snake Game">
                        <div class="card-body">
                            <h5 class="card-title">Snake</h5>
                            <p class="card-text">
                                In the Snake game, you control a growing snake that must navigate a grid to eat food while avoiding collisions with its own tail and the edges of the screen.
                            </p>
                            <a href="games/snake/" class="btn">Play Now</a>
                        </div>
                    </div>
                    <?php /*
                    <div class="card">
                        <img src="" alt="PacMan Game">
                        <div class="card-body">
                            <h5 class="card-title">PacMan</h5>
                            <p class="card-text">
                                In Pac-Man, you guide a yellow, circular character through a maze, eating pellets while avoiding colorful ghosts that roam the maze.
                            </p>
                            <a href="games/pacman/" class="btn">Play Now</a>
                        </div>
                    </div>  
                    <div class="card">
                        <img src="" alt="Tetris Game">
                        <div class="card-body">
                            <h5 class="card-title">Tetris</h5>
                            <p class="card-text">
                                In Tetris, you manipulate falling geometric shapes to complete horizontal lines on a grid, which clears the lines and prevents the shapes from stacking up to the top of the screen.
                            </p>
                            <a href="games/tetris/" class="btn">Play Now</a>
                        </div>
                    </div>
                    */ ?>
                    <div class="card">
                        <img src="" alt="Doodle jump">
                        <div class="card-body">
                            <h5 class="card-title">Doodle jump</h5>
                            <p class="card-text">
                                Doodle Jump is an endless vertical platformer game where you guide a bouncing character upward, avoiding obstacles and collecting power-ups to achieve the highest possible score.
                            </p>
                            <a href="games/doodlejump/" class="btn">Play Now</a>
                        </div>
                    </div>
                </div>
            </div>

        </main>
        <?php require 'inc/footer.php' ?>
    </div>
</body>

</html>