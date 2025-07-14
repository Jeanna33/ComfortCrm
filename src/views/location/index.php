<?php
$this->title = 'Автоотслеживание координат';
?>
<h3><?= $this->title ?></h3>
<div id="status">Ожидание...</div>
<button id="start">Старт</button>
<button id="stop" style="display:none;">Стоп</button>

<script>
    let geoInterval = null;
    const intervalSeconds = 10;
    let geoPoints = [];

    function getAndSaveLocation() {
        if (!navigator.geolocation) {
            document.getElementById('status').textContent = 'Геолокация не поддерживается';
            return;
        }

        navigator.geolocation.getCurrentPosition(position => {
            const point = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
                timestamp: Date.now()
            };
            geoPoints.push(point);
            localStorage.setItem('geo_track', JSON.stringify(geoPoints));
            document.getElementById('status').textContent =
                `Получено: ${point.lat}, ${point.lng}`;
        }, () => {
            document.getElementById('status').textContent = 'Ошибка получения координат';
        });
    }

    document.getElementById('start').addEventListener('click', () => {
        geoPoints = JSON.parse(localStorage.getItem('geo_track') || '[]');
        getAndSaveLocation(); // первый вызов сразу
        geoInterval = setInterval(getAndSaveLocation, intervalSeconds * 1000);

        document.getElementById('start').style.display = 'none';
        document.getElementById('stop').style.display = 'inline';
        document.getElementById('status').textContent = 'Отслеживание запущено';
    });

    document.getElementById('stop').addEventListener('click', () => {
        clearInterval(geoInterval);
        document.getElementById('start').style.display = 'inline';
        document.getElementById('stop').style.display = 'none';
        document.getElementById('status').textContent = 'Остановлено';
    });
</script>
