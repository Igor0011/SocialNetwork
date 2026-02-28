const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const box = 20;
let score = 0;

let snake = [];
snake[0] = { x: 9 * box, y: 10 * box };

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

        var gameScores = [
            {score: score, game: 1, gameName: "Snake"},
            // {score: 200, game: 2, gameName: "Pac-Man"},
            // {score: 300, game: 3, gameName: "Tetris"}
        ];
    
        // AJAX request to send data to server
        $.ajax({
            url: '../../ajx/ajxInsertGameScore.php',  // PHP script that handles the insert
            type: 'POST',
            data: { gameScores: JSON.stringify(gameScores) },  // Send array as JSON string
            success: function(response) {
                alert('Game scores inserted successfully: ' + response);
            },
            error: function() {
                alert('Error occurred while inserting game scores.');
            }
        });
    }

    snake.unshift(newHead);

    ctx.fillStyle = 'black';
    ctx.font = '30px Arial';
    ctx.fillText('Score: ' + score, box, box);

    
}

const game = setInterval(draw, 100);
