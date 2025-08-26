<?php
class Response {
    private $response;

    public function __construct($response) {
        $this->response = $response;
    }

    public function respond() {
        if (!empty($this->response['isAjax'])) {
            switch ($this->response['action']) {
                case 'loadPage':
                    switch ($this->response['page']) {
                        case 'home':
                            $coreContent = $this->homeCore();
                            break;
                        case 'howTo':
                            $coreContent = $this->howToCore();
                            break;
                        case 'game':
                            $coreContent = $this->gameCore();
                            break;
						case 'success':
							$coreContent = $this->successCore();
							break;
                        default:
                            $coreContent = "<h1>Page not found</h1>";
                    }
                    echo json_encode(['html' => $coreContent]);
                    break;

                case 'nextLevel':
                    $coreContent = $this->gameCore();
                    echo json_encode(['html' => $coreContent]);
                    break;
            }
        } else {
            if ($this->response['action'] === 'GET') {
                switch ($this->response['page']) {
                    case 'home':
                        $this->showPage([$this, 'homeCore']);
                        break;
                    case 'howTo':
                        $this->showPage([$this, 'howToCore']);
                        break;
                    case 'game':
                        $this->showPage([$this, 'gameCore']);
                        break;
         
                }
            }
        }
    }

    private function showPage(array $showPageCore) {
        $this->startDoc();
        echo "<div id='core'>";
        echo call_user_func($showPageCore);
        echo "</div>";
        $this->endDoc();
    }

    private function showMenuItems() {
        echo "
        <div id='topBar' class='topBar'>
            <span id='menuButtons' class='menuButtons'>";
                echo $this->showMenuBtn('homeBtn', 'https://cdn-icons-png.flaticon.com/256/25/25694.png');
                echo $this->showMenuBtn('howToBtn', 'https://cdn-icons-png.flaticon.com/512/5726/5726532.png');
        echo "
            </span>
        </div>
        ";
    }

    private function showMenuBtn($id, $imgSrc) {
        return "
        <button class='menuBtn $id'>
            <img src='$imgSrc' alt='$id'>
        </button>
        ";
    }

    private function homeCore() {
        return "<h1>The Mouse Maze</h1>
        <button class='playBtn'>PLAY!</button>";
    }

    private function howToCore() {
        return "<h1>How to play</h1>
        <p>Click the red button to start the game. Move the mouse through the maze 
        without hitting the walls. To finish the level reach the green square.</p>
        <button class='playBtn'>PLAY!</button>";
    }

    private function gameCore() {
        $currentLevel = $_SESSION['currentLevel'] ?? 1;
        $levelDir = "levels/level_" . $currentLevel.".png";
		
        return "<div class = 'centerDiv'>
		<h1>Level: " . $currentLevel . "</h1>
		</div>
		<div class = 'centerDiv'>
        <canvas id='game_canvas' 
            width='600' 
            height='600' 
            data-level='" . htmlspecialchars($levelDir) . "'>
        </canvas>
		</div>
		<div class = 'centerDiv'>
		<button id='resetBtn'>Reset level</button>
		<button id='nextLevelBtn'>Next level</button>
		</div>
		";
    }

    private function gameOverCore() {
        return "<h1>GAME OVER</h1>
        <button id='resetBtn' class='resetBtn'>Start over</button>";
    }

    private function successCore() {
        return "<h1>Yeah you did it</h1>
        <button id='resetPlayBtn' class='playBtn'>PLAY AGAIN!</button>";
    }

    private function startDoc() {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
        ";
		echo "<script src='https://code.jquery.com/jquery-3.7.1.min.js'></script>";
        require_once("constants/javascript_files.php");
		echo "<link rel='stylesheet' href = 'stylesheets/styles.css'>";
        foreach ($javascriptFileNames as $fileName) {
            echo "<script src='javascript/" . $fileName . "'></script>";
        }
        echo "
        </head>
        <body>
        ";
        $this->showMenuItems();
    }

    private function endDoc() {
        echo "
        </body>
        </html>
        ";
    }
}
