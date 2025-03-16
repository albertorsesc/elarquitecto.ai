<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';

// Game constants
const CELL_SIZE = 20;
const PACMAN_SIZE = CELL_SIZE - 2;
const GHOST_SIZE = CELL_SIZE - 2;
const DOT_SIZE = 4;
const POWER_DOT_SIZE = 8;
const WALL_COLOR = '#2563eb';
const DOT_COLOR = '#60a5fa';
const POWER_DOT_COLOR = '#3b82f6';
const PACMAN_COLOR = '#fbbf24';
const GHOST_COLORS = ['#ef4444', '#ec4899', '#8b5cf6', '#10b981'];

// Maze layout
// 0: Empty, 1: Wall, 2: Dot, 3: Power Pellet, 4: Ghost House
const MAZE_LAYOUT = [
  [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1],
  [1,2,2,2,2,2,2,2,2,1,2,2,2,2,2,2,2,2,1],
  [1,3,1,1,2,1,1,1,2,1,2,1,1,1,2,1,1,3,1],
  [1,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,1],
  [1,2,1,1,2,1,2,1,1,1,1,1,2,1,2,1,1,2,1],
  [1,2,2,2,2,1,2,2,2,1,2,2,2,1,2,2,2,2,1],
  [1,1,1,1,2,1,1,1,0,1,0,1,1,1,2,1,1,1,1],
  [1,1,1,1,2,1,0,0,0,0,0,0,0,1,2,1,1,1,1],
  [1,1,1,1,2,1,0,1,1,4,1,1,0,1,2,1,1,1,1],
  [0,0,0,0,2,0,0,1,0,0,0,1,0,0,2,0,0,0,0],
  [1,1,1,1,2,1,0,1,1,1,1,1,0,1,2,1,1,1,1],
  [1,1,1,1,2,1,0,0,0,0,0,0,0,1,2,1,1,1,1],
  [1,1,1,1,2,1,0,1,1,1,1,1,0,1,2,1,1,1,1],
  [1,2,2,2,2,2,2,2,2,1,2,2,2,2,2,2,2,2,1],
  [1,2,1,1,2,1,1,1,2,1,2,1,1,1,2,1,1,2,1],
  [1,3,2,1,2,2,2,2,2,2,2,2,2,2,2,1,2,3,1],
  [1,1,2,1,2,1,2,1,1,1,1,1,2,1,2,1,2,1,1],
  [1,2,2,2,2,1,2,2,2,1,2,2,2,1,2,2,2,2,1],
  [1,2,1,1,1,1,1,1,2,1,2,1,1,1,1,1,1,2,1],
  [1,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,1],
  [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1]
];

// Game state
const gameCanvas = ref<HTMLCanvasElement | null>(null);
const gameStarted = ref(false);
const score = ref(0);
const lives = ref(3);
const maze = ref(JSON.parse(JSON.stringify(MAZE_LAYOUT)));

// Game objects
const pacman = ref({
  gridX: 9,
  gridY: 15,
  x: 0,
  y: 0,
  direction: 0,
  nextDirection: 0,
  speed: 2,
  mouthOpen: 0,
  moving: false
});

// Game loop
let animationFrame: number;
let lastTime = 0;
const canvasContainer = ref<HTMLDivElement | null>(null);

// Helper functions
const gridToPixel = (grid: number, cellSize: number) => grid * cellSize + cellSize / 2;

const isValidMove = (gridX: number, gridY: number) => {
  if (gridX < 0 || gridX >= maze.value[0].length || gridY < 0 || gridY >= maze.value.length) {
    return false;
  }
  return maze.value[gridY][gridX] !== 1;
};

const tryMove = (direction: number) => {
  const currentGridX = Math.floor(pacman.value.x / CELL_SIZE);
  const currentGridY = Math.floor(pacman.value.y / CELL_SIZE);

  let nextGridX = currentGridX;
  let nextGridY = currentGridY;

  switch (direction) {
    case 0: // Right
      nextGridX += 1;
      break;
    case Math.PI: // Left
      nextGridX -= 1;
      break;
    case -Math.PI / 2: // Up
      nextGridY -= 1;
      break;
    case Math.PI / 2: // Down
      nextGridY += 1;
      break;
  }

  if (isValidMove(nextGridX, nextGridY)) {
    pacman.value.moving = true;
    pacman.value.direction = direction;

    // Handle dot collection
    if (maze.value[nextGridY][nextGridX] === 2) {
      maze.value[nextGridY][nextGridX] = 0;
      score.value = Math.min(1000, score.value + 10);
    } else if (maze.value[nextGridY][nextGridX] === 3) {
      maze.value[nextGridY][nextGridX] = 0;
      score.value = Math.min(1000, score.value + 50);
      // TODO: Implement power pellet effects
    }
  }
};

const initGame = () => {
  if (!gameCanvas.value) return;
  const ctx = gameCanvas.value.getContext('2d');
  if (!ctx) return;

  // Reset game state
  score.value = 0;
  maze.value = JSON.parse(JSON.stringify(MAZE_LAYOUT));
  pacman.value = {
    gridX: 9,
    gridY: 15,
    x: gridToPixel(9, CELL_SIZE),
    y: gridToPixel(15, CELL_SIZE),
    direction: 0,
    nextDirection: 0,
    speed: 2,
    mouthOpen: 0,
    moving: false
  };

  // Set up the game board responsively
  updateCanvasSize();

  // Start game loop
  gameLoop(0);
  gameStarted.value = true;

  // Focus the game canvas
  gameCanvas.value.focus();
};

const stopGame = () => {
  if (animationFrame) {
    cancelAnimationFrame(animationFrame);
  }
  gameStarted.value = false;
  pacman.value = {
    gridX: 9,
    gridY: 15,
    x: gridToPixel(9, CELL_SIZE),
    y: gridToPixel(15, CELL_SIZE),
    direction: 0,
    nextDirection: 0,
    speed: 2,
    mouthOpen: 0,
    moving: false
  };
};

const updateCanvasSize = () => {
  if (!gameCanvas.value || !canvasContainer.value) return;

  // Get container dimensions
  const containerWidth = canvasContainer.value.clientWidth;
  const containerHeight = canvasContainer.value.clientHeight;

  // Calculate the maximum size while maintaining aspect ratio (28:31)
  const aspectRatio = 28/31;
  let width = containerWidth - 32; // Account for padding
  let height = width / aspectRatio;

  if (height > containerHeight - 32) {
    height = containerHeight - 32;
    width = height * aspectRatio;
  }

  // Update canvas size
  gameCanvas.value.width = width;
  gameCanvas.value.height = height;

  // Update cell size based on new dimensions
  const newCellSize = width / 28;
  pacman.value.x = gridToPixel(pacman.value.gridX, newCellSize);
  pacman.value.y = gridToPixel(pacman.value.gridY, newCellSize);
};

const drawMaze = (ctx: CanvasRenderingContext2D) => {
  const cellSize = gameCanvas.value!.width / maze.value[0].length;

  for (let y = 0; y < maze.value.length; y++) {
    for (let x = 0; x < maze.value[y].length; x++) {
      const cell = maze.value[y][x];
      const pixelX = x * cellSize;
      const pixelY = y * cellSize;

      switch (cell) {
        case 1: // Wall
          ctx.fillStyle = WALL_COLOR;
          ctx.shadowBlur = 5;
          ctx.shadowColor = WALL_COLOR;
          ctx.fillRect(pixelX, pixelY, cellSize, cellSize);
          ctx.shadowBlur = 0;
          break;

        case 2: // Dot
          ctx.beginPath();
          ctx.fillStyle = DOT_COLOR;
          ctx.shadowBlur = 3;
          ctx.shadowColor = DOT_COLOR;
          ctx.arc(pixelX + cellSize/2, pixelY + cellSize/2, DOT_SIZE/2, 0, Math.PI * 2);
          ctx.fill();
          ctx.shadowBlur = 0;
          break;

        case 3: // Power Pellet
          ctx.beginPath();
          ctx.fillStyle = POWER_DOT_COLOR;
          ctx.shadowBlur = 8;
          ctx.shadowColor = POWER_DOT_COLOR;
          ctx.arc(pixelX + cellSize/2, pixelY + cellSize/2, POWER_DOT_SIZE/2, 0, Math.PI * 2);
          ctx.fill();
          ctx.shadowBlur = 0;
          break;
      }
    }
  }
};

const gameLoop = (timestamp: number) => {
  if (!gameCanvas.value) return;
  const ctx = gameCanvas.value.getContext('2d');
  if (!ctx) return;

  // Calculate delta time
  const deltaTime = timestamp - lastTime;
  lastTime = timestamp;

  // Update Pacman position
  if (pacman.value.moving) {
    const speed = (pacman.value.speed * deltaTime) / 16;
    const nextX = pacman.value.x + Math.cos(pacman.value.direction) * speed;
    const nextY = pacman.value.y + Math.sin(pacman.value.direction) * speed;

    // Check if the next position is valid
    const nextGridX = Math.floor(nextX / CELL_SIZE);
    const nextGridY = Math.floor(nextY / CELL_SIZE);

    if (isValidMove(nextGridX, nextGridY)) {
      pacman.value.x = nextX;
      pacman.value.y = nextY;
    } else {
      pacman.value.moving = false;
    }
  }

  // Clear canvas
  ctx.fillStyle = 'rgba(0, 0, 0, 0.8)';
  ctx.fillRect(0, 0, gameCanvas.value.width, gameCanvas.value.height);

  // Draw maze
  drawMaze(ctx);

  // Draw Pacman with mouth animation
  pacman.value.mouthOpen = Math.sin(timestamp / 100) * 0.3;
  drawPacman(ctx);

  // Request next frame
  animationFrame = requestAnimationFrame(gameLoop);
};

const drawPacman = (ctx: CanvasRenderingContext2D) => {
  ctx.save();
  ctx.translate(pacman.value.x, pacman.value.y);
  ctx.rotate(pacman.value.direction);

  // Draw Pacman body with neon effect
  ctx.beginPath();
  ctx.fillStyle = PACMAN_COLOR;
  ctx.shadowBlur = 15;
  ctx.shadowColor = PACMAN_COLOR;
  ctx.arc(0, 0, PACMAN_SIZE / 2, pacman.value.mouthOpen, 2 * Math.PI - pacman.value.mouthOpen);
  ctx.lineTo(0, 0);
  ctx.fill();
  ctx.shadowBlur = 0;

  ctx.restore();
};

// Keyboard controls
const handleKeydown = (e: KeyboardEvent) => {
  if (!gameStarted.value) return;

  switch (e.key) {
    case 'ArrowLeft':
      tryMove(Math.PI);
      break;
    case 'ArrowRight':
      tryMove(0);
      break;
    case 'ArrowUp':
      tryMove(-Math.PI / 2);
      break;
    case 'ArrowDown':
      tryMove(Math.PI / 2);
      break;
    case 'Escape':
      stopGame();
      break;
  }
  e.preventDefault();
};

const handleKeyup = (e: KeyboardEvent) => {
  if (['ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown'].includes(e.key)) {
    // Keep moving until hitting a wall
    // pacman.value.moving = false;
  }
};

// Window resize handler
const handleResize = () => {
  if (gameStarted.value) {
    updateCanvasSize();
  }
};

// Lifecycle hooks
onMounted(() => {
  window.addEventListener('keydown', handleKeydown);
  window.addEventListener('keyup', handleKeyup);
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
  window.removeEventListener('keyup', handleKeyup);
  window.removeEventListener('resize', handleResize);
  if (animationFrame) {
    cancelAnimationFrame(animationFrame);
  }
});
</script>

<template>
  <div
    ref="canvasContainer"
    class="game-container"
  >
    <div class="game-header">
      <div class="score">Score: {{ score }}</div>
      <div class="lives">Lives: {{ lives }}</div>
    </div>

    <canvas
      ref="gameCanvas"
      class="game-canvas"
      tabindex="0"
      @click="!gameStarted && initGame()"
    />

    <div v-if="!gameStarted" class="start-overlay">
      <button
        class="start-button"
        @click="initGame"
      >
        {{ animationFrame ? 'Continue' : 'Start Game' }}
      </button>
      <p class="mt-4 text-sm text-foreground/70">Use arrow keys to move, ESC to pause</p>
    </div>
  </div>
</template>

<style scoped>
.game-container {
  @apply relative mx-auto w-full max-w-2xl rounded-xl border border-white/20 bg-background/80 p-4 backdrop-blur-sm;
  aspect-ratio: 28/31;
}

.game-header {
  @apply absolute top-2 left-0 right-0 z-10 flex justify-between px-6 text-lg font-bold text-cyan-400;
}

.game-canvas {
  @apply block h-full w-full rounded-lg border border-white/10 outline-none;
}

.start-overlay {
  @apply absolute inset-0 flex flex-col items-center justify-center bg-black/50 backdrop-blur-sm;
}

.start-button {
  @apply rounded-lg bg-cyan-500 px-6 py-3 font-bold text-white transition-all hover:bg-cyan-600 hover:shadow-[0_0_15px_rgba(6,182,212,0.5)];
}

/* Neon border effect */
.game-container::before {
  content: '';
  @apply absolute inset-0 rounded-xl;
  background: linear-gradient(90deg, #06b6d4, #3b82f6, #8b5cf6);
  z-index: -1;
  margin: -1px;
  animation: borderGlow 3s linear infinite;
}

@keyframes borderGlow {
  0%, 100% {
    opacity: 0.5;
  }
  50% {
    opacity: 1;
  }
}
</style>