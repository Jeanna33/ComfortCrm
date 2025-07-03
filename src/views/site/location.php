<?php
?>
<button id="startTracking">Начать отслеживание (30 мин)</button>
<button id="stopTracking" style="display:none;">Остановить</button>
<div id="status">Ожидание...</div>

<script>
    let watchId = null;
    let trackingTimer = null;
    const durationMinutes = 30;

    function sendCoords(lat, lng) {
        $.post('/location/save', {
            lat: lat,
            lng: lng,
            _csrf: yii.getCsrfToken()
        }).done(() => {
            $('#status').text('Координаты отправлены: ' + lat + ', ' + lng);
        }).fail(() => {
            $('#status').text('Ошибка при отправке координат');
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function () {
            let watchId = null;
            let geoData = [];

            $('#startTracking').on('click', function () {
                if (!navigator.geolocation) {
                    alert('Геолокация не поддерживается');
                    return;
                }

                $('#startTracking').hide();
                $('#stopTracking').show();
                $('#status').text('Начато отслеживание...');

                watchId = navigator.geolocation.watchPosition(function (position) {
                    const point = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                        timestamp: Date.now()
                    };
                    geoData.push(point);
                    localStorage.setItem('geo_track', JSON.stringify(geoData));

                    $('#status').text(`Последняя точка: ${point.lat}, ${point.lng}`);
                }, function (err) {
                    console.error(err);
                    $('#status').text('Ошибка получения координат');
                }, {
                    enableHighAccuracy: true,
                    maximumAge: 10000,
                    timeout: 10000
                });
            });

            $('#stopTracking').on('click', function () {
                if (watchId !== null) {
                    navigator.geolocation.clearWatch(watchId);
                    watchId = null;
                }
                $('#startTracking').show();
                $('#stopTracking').hide();
                $('#status').text('Отслеживание остановлено');
            });

// Для проверки
            const saved = localStorage.getItem('geo_track');
            if (saved) {
                geoData = JSON.parse(saved);
                console.log('Сохранённые точки:', geoData);
            }
        })
    });



    function stopTracking() {
        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
        if (trackingTimer !== null) {
            clearTimeout(trackingTimer);
            trackingTimer = null;
        }
        $('#startTracking').show();
        $('#stopTracking').hide();
    }
</script>