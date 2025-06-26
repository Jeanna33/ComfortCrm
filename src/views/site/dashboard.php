<?php
use app\assets\AppAsset;
AppAsset::register($this);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Настройки</title>
    <style>
        body {
            background-color: #f3f4f6;
        }
        .card {
            min-height: 250px;
            padding: 15px;
            margin-bottom: 20px;
            border: none;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .btn {
            display: block;
            margin: 10px 0;
            padding: 12px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: move;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .card.drop-highlight {
            background-color: #e5e7eb;
            border: 2px dashed #6b7280;
        }
        .btn-antistress {
            background: linear-gradient(45deg, #34d399, #60a5fa);
            color: white;
            border: none;
        }
        .btn-antistress:hover {
            background: linear-gradient(45deg, #2dd4bf, #3b82f6);
        }
        #pomodoroTimer {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            color: #dc3545; /* Matches btn-danger color for Pomodoro */
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="card col-md-4" ondragover="allowDrop(event)" ondrop="drop(event)" id="card1">
            <input type="button" class="btn btn-primary" value="Заказы" draggable="true" ondragstart="drag(event)" id="button-0">
            <input type="button" class="btn btn-secondary" value="Рабочие контакты" draggable="true" ondragstart="drag(event)" id="button-1">
            <input type="button" class="btn btn-warning" value="Финансы" draggable="true" ondragstart="drag(event)" id="button-2">
        </div>
        <div class="card col-md-4" ondragover="allowDrop(event)" ondrop="drop(event)" id="card2">
        </div>
        <div class="card col-md-4" ondragover="allowDrop(event)" ondrop="drop(event)" id="card3">
            <input type="button" class="btn btn-success" value="Распорядок дня" draggable="true" ondragstart="drag(event)" id="button-3">
            <input type="button" class="btn btn-info" value="Треки" draggable="true" ondragstart="drag(event)" id="button-4">
            <input type="button" class="btn btn-light" value="Календарь" draggable="true" ondragstart="drag(event)" id="button-5">
            <input type="button" class="btn btn-danger" value="Помодоро" draggable="true" ondragstart="drag(event)" id="button-6">
            <input type="button" class="btn btn-success" value="Прогулки" draggable="true" ondragstart="drag(event)" id="button-7">
            <input type="button" class="btn btn-link" value="Дневник" draggable="true" ondragstart="drag(event)" id="button-8">
            <input type="button" class="btn btn-antistress" value="Антистресс" draggable="true" ondragstart="drag(event)" id="button-9">
        </div>
    </div>
</div>

<!-- Bootstrap Modal for Pomodoro Timer -->
<div class="modal fade" id="pomodoroModal" tabindex="-1" aria-labelledby="pomodoroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pomodoroModalLabel">Помодоро Таймер</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="pomodoroTimer">25:00</div>
                <div class="text-center mt-4">
                    <button class="btn btn-success" id="startTimer">Старт</button>
                    <button class="btn btn-warning" id="pauseTimer" disabled>Пауза</button>
                    <button class="btn btn-danger" id="resetTimer">Сброс</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function drag(event) {
        event.dataTransfer.setData("text", event.target.id);
        event.target.classList.add("dragging");
    }

    function allowDrop(event) {
        event.preventDefault();
        event.currentTarget.classList.add("drop-highlight");
    }

    function drop(event) {
        event.preventDefault();
        const data = event.dataTransfer.getData("text");
        const draggedElement = document.getElementById(data);
        if (draggedElement) {
            event.currentTarget.appendChild(draggedElement);
            saveButtonPositions();
        }
        event.currentTarget.classList.remove("drop-highlight");
    }

    function saveButtonPositions() {
        const cards = document.querySelectorAll('.card');
        const positions = {};
        cards.forEach(card => {
            const buttonIds = Array.from(card.querySelectorAll('.btn')).map(btn => btn.id);
            positions[card.id] = buttonIds;
        });
        localStorage.setItem('buttonPositions', JSON.stringify(positions));
    }

    function loadButtonPositions() {
        const positions = JSON.parse(localStorage.getItem('buttonPositions'));
        if (!positions) return;

        document.querySelectorAll('.card').forEach(card => {
            card.innerHTML = '';
        });

        Object.keys(positions).forEach(cardId => {
            const card = document.getElementById(cardId);
            positions[cardId].forEach(buttonId => {
                const button = createButton(buttonId);
                if (button) {
                    card.appendChild(button);
                }
            });
        });
    }

    function createButton(buttonId) {
        const buttonData = {
            'button-0': { class: 'btn-primary', value: 'Заказы' },
            'button-1': { class: 'btn-secondary', value: 'Рабочие контакты' },
            'button-2': { class: 'btn-warning', value: 'Финансы' },
            'button-3': { class: 'btn-success', value: 'Распорядок дня' },
            'button-4': { class: 'btn-info', value: 'Треки' },
            'button-5': { class: 'btn-light', value: 'Календарь' },
            'button-6': { class: 'btn-danger', value: 'Помодоро' },
            'button-7': { class: 'btn-success', value: 'Прогулки' },
            'button-8': { class: 'btn-link', value: 'Дневник' },
            'button-9': { class: 'btn-antistress', value: 'Антистресс' }
        };

        if (buttonData[buttonId]) {
            const button = document.createElement('input');
            button.type = 'button';
            button.className = `btn ${buttonData[buttonId].class}`;
            button.value = buttonData[buttonId].value;
            button.draggable = true;
            button.id = buttonId;
            button.ondragstart = drag;
            return button;
        }
        return null;
    }

    // Pomodoro Timer Logic
    let timerInterval = null;
    let timeLeft = 25 * 60; // 25 minutes in seconds
    const timerDisplay = document.getElementById('pomodoroTimer');
    const startButton = document.getElementById('startTimer');
    const pauseButton = document.getElementById('pauseTimer');
    const resetButton = document.getElementById('resetTimer');

    function updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }

    function startTimer() {
        if (!timerInterval) {
            timerInterval = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                    startButton.disabled = false;
                    pauseButton.disabled = true;
                    alert('Помодоро завершен!');
                    resetTimer();
                    return;
                }
                timeLeft--;
                updateTimerDisplay();
            }, 1000);
            startButton.disabled = true;
            pauseButton.disabled = false;
        }
    }

    function pauseTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
            timerInterval = null;
            startButton.disabled = false;
            pauseButton.disabled = true;
        }
    }

    function resetTimer() {
        clearInterval(timerInterval);
        timerInterval = null;
        timeLeft = 25 * 60;
        updateTimerDisplay();
        startButton.disabled = false;
        pauseButton.disabled = true;
    }



    document.addEventListener('dragend', function(event) {
        document.querySelectorAll('.card').forEach(card => card.classList.remove('drop-highlight'));
    });

    document.addEventListener('DOMContentLoaded', loadButtonPositions);

    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {
            // Open modal on Pomodoro button click
            $("#button-6").on("click", function() {
                const modal = new bootstrap.Modal(document.getElementById('pomodoroModal'));
                modal.show();
            });

            // Timer button event listeners
            startButton.addEventListener('click', startTimer);
            pauseButton.addEventListener('click', pauseTimer);
            resetButton.addEventListener('click', resetTimer);

            $('#button-0').click(function() {
                alert('click');
                window.open('/site/orders');
            });
        });

    });
</script>


</body>
</html>