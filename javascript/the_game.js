$(document).ready(function () {

    // =============================
    // Reusable AJAX loader for buttons
    // =============================
	function clickButton(btn, page){
		const core = $('#core');
		$.ajax({
			type: 'POST',
			url: 'index.php',
			data: { action: 'loadPage', isAjax: 1, page: page },
			dataType: 'json',
			success: function (data) {
				if (data && data.html) {
					core.html(data.html);
					console.log(core.html());

					// Only initialize game if we just loaded the game page
					if (page === 'game') {
						console.log('Initializing the game setup');
						initializeGameAndButtons();
					}
				}
			},
			error: function (err) {
				console.error("Error:", err);
			}
		});
	}
	
    function loadButton(btn, page) {
    $(btn).on("click", function() {
        clickButton(btn, page);
    });
}

    // Attach AJAX to buttons
    $('#core').on('click', '.playBtn', function() {
		clickButton(this, 'game');
	});
    $(".howToBtn").each(function () { loadButton(this, 'howTo'); });
    $(".homeBtn").each(function () { loadButton(this, 'home'); });

    // =============================
    // Game canvas setup (only if canvas exists)
    // =============================
    
	function initializeGameAndButtons(){
		const canvas = $('#game_canvas')[0];
		if (!canvas) {
			console.log("No canvas on this page, skipping game setup.");
			return; // stop running game setup if no canvas
		}

		const ctx = canvas.getContext('2d', { willReadFrequently: true });
		const levelDirectory = canvas.dataset.level;
		const img = new Image();
		img.src = levelDirectory;

		const restartLevelButton = $('#resetBtn')[0];
		console.log(restartLevelButton);
		const nextLevelButton = $('#nextLevelBtn')[0];
		console.log(nextLevelButton);

		function drawLevel() {
			ctx.clearRect(0, 0, canvas.width, canvas.height);
			ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
			if (restartLevelButton) restartLevelButton.style.display = "none";
			if (nextLevelButton) nextLevelButton.style.display = "none";
		}

		img.onload = drawLevel;

		// Restart level button
		if (restartLevelButton) {
			$(restartLevelButton).on('click', function () {
				drawLevel();
			});
		}

		// Next level button
		if (nextLevelButton) {
			$(nextLevelButton).on('click', function () {
				$.ajax({
					type: 'POST',
					url: 'index.php',
					data: { action: 'nextLevel', isAjax: 1 },
					dataType: 'json',
					success: function (data) {
						if (data && data.html) {
							$('#core').html(data.html);
							initializeGameAndButtons();
						}
					},
					error: function (err) {
						console.error(err);
					}
				});
			});
		}
		
		let playing = false;
		// Mouse position
		$(canvas).on('mousemove', function (e) {
			const mouseX = e.offsetX;
			const mouseY = e.offsetY;
			const colorData = ctx.getImageData(mouseX, mouseY, 1, 1).data;
		
		// Mouse start detection
		if (!playing){
			isRed = (colorData[0] > 200 && colorData[1] < 50 && colorData[2] < 50)
			if (isRed){
				playing = true;
				console.log('The game has started')
				document.body.style.cursor = "crosshair";

			}
		}
		
			if (playing){
			// Mouse collision detection
				if (colorData[0] < 50 && colorData[1] < 50 && colorData[2] < 50) {
					console.log('Mouse collision detection');
					playing = false;
					ctx.clearRect(0, 0, canvas.width, canvas.height);
					document.body.style.cursor = "default";
					if (restartLevelButton) {
						restartLevelButton.style.display = "inline-block";
					}
				}
			//Mouse finish detection
				if (colorData[0] < 50 && colorData[1] > 200 && colorData[2] < 50) {
					console.log('mouse finish detection');
					playing = false;
					ctx.clearRect(0, 0, canvas.width, canvas.height);
					document.body.style.cursor = "default";
					if (nextLevelButton) {
						nextLevelButton.style.display = "inline-block";
					}
				}
			}
		});
	}
	initializeGameAndButtons();
});
