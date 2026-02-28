const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');

// Maze layout: 1 is a wall, 0 is an open space
const maze = [
    [1,1,1,1,1,1,1,1,1,1],
    [1,0,0,0,0,1,0,0,0,1],
    [1,0,1,1,0,1,0,1,0,1],
    [1,0,1,0,0,0,0,1,0,1],
    [1,0,1,0,1,1,0,1,0,1],
    [1,0,0,0,0,0,0,1,0,1],
    [1,1,1,1,1,1,0,1,0,1],
    [1,0,0,0,0,0,0,0,0,1],
    [1,0,1,1,1,0,1,1,0,1],
    [1,1,1,1,1,1,1,1,1,1]
];

const tileSize = 40; // Size of each tile in the maze

// Draw maze
function drawMaze() {
    ctx.fillStyle = 'blue'; // Wall color
    for (let row = 0; row < maze.length; row++) {
        for (let col = 0; col < maze[row].length; col++) {
            if (maze[row][col] === 1) {
                ctx.fillRect(col * tileSize, row * tileSize, tileSize, tileSize);
            }
        }
    }
}

// Pac-Man properties
const pacMan = {
    x: 2 * tileSize + tileSize / 2,
    y: 1 * tileSize + tileSize / 2,
    size: tileSize / 2,
    speed: 2,
    direction: 'right', // Initial direction
    color: 'yellow'
};

// Ghost properties
const ghosts = [
    { x: 6 * tileSize + tileSize / 2, y: 2 * tileSize + tileSize / 2, size: tileSize / 2, color: 'red', dx: 1, dy: 0 }
];

// Draw Pac-Man
function drawPacMan() {
    ctx.beginPath();
    ctx.arc(pacMan.x, pacMan.y, pacMan.size, 0.2 * Math.PI, 1.8 * Math.PI); // Drawing Pac-Man's mouth
    ctx.lineTo(pacMan.x, pacMan.y); // To close the mouth
    ctx.closePath();
    ctx.fillStyle = pacMan.color;
    ctx.fill();
}

// Draw Ghosts
function drawGhosts() {
    ghosts.forEach(ghost => {
        ctx.beginPath();
        ctx.arc(ghost.x, ghost.y, ghost.size, 0, 2 * Math.PI);
        ctx.closePath();
        ctx.fillStyle = ghost.color;
        ctx.fill();
    });
}

// Update Pac-Man's position
function updatePacMan() {
    let newX = pacMan.x;
    let newY = pacMan.y;

    // Update new position based on current direction
    switch (pacMan.direction) {
        case 'up':
            newY -= pacMan.speed;
            break;
        case 'down':
            newY += pacMan.speed;
            break;
        case 'left':
            newX -= pacMan.speed;
            break;
        case 'right':
            newX += pacMan.speed;
            break;
    }

    const gridX = Math.floor(newX / tileSize);
    const gridY = Math.floor(newY / tileSize);

    if (maze[gridY] && maze[gridY][gridX] === 0) {
        pacMan.x = newX;
        pacMan.y = newY;
    }
}

// Update Ghosts' position
function updateGhosts() {
    ghosts.forEach(ghost => {
        const targetX = pacMan.x;
        const targetY = pacMan.y;

        const dx = targetX - ghost.x;
        const dy = targetY - ghost.y;
        const distance = Math.sqrt(dx * dx + dy * dy);

        if (distance < tileSize) {
            ghost.dx = (dx / distance) * ghost.speed;
            ghost.dy = (dy / distance) * ghost.speed;
        }

        const newX = ghost.x + ghost.dx;
        const newY = ghost.y + ghost.dy;

        const gridX = Math.floor(newX / tileSize);
        const gridY = Math.floor(newY / tileSize);

        if (maze[gridY] && maze[gridY][gridX] === 0) {
            ghost.x = newX;
            ghost.y = newY;
        }
    });
}

// Clear canvas and draw everything
function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawMaze();
    drawPacMan();
    drawGhosts();
}

// Game loop
function gameLoop() {
    updatePacMan();
    updateGhosts();
    draw();
    requestAnimationFrame(gameLoop);
}

// Control Pac-Man with arrow keys
document.addEventListener('keydown', (e) => {
    switch (e.key) {
        case 'ArrowUp':
            pacMan.direction = 'up';
            break;
        case 'ArrowDown':
            pacMan.direction = 'down';
            break;
        case 'ArrowLeft':
            pacMan.direction = 'left';
            break;
        case 'ArrowRight':
            pacMan.direction = 'right';
            break;
    }
});

gameLoop();
