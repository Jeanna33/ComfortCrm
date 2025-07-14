<?php
$this->title = 'Маршрут на карте (OpenLayers 10.6)';
$this->registerCssFile('@web/css/ol.min.css');
$this->registerJsFile('@web/js/ol.js', ['position' => \yii\web\View::POS_HEAD]);
?>

<h3><?= $this->title ?></h3>
<div id="map" style="width: 100%; height: 500px;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const saved = localStorage.getItem('geo_track');
        if (!saved) {
            alert('Нет сохранённых координат');
            return;
        }

        const points = JSON.parse(saved);
        if (points.length === 0) return;

        const coordinates = points.map(p => ol.proj.fromLonLat([p.lng, p.lat]));

        const route = new ol.geom.LineString(coordinates);
        const routeFeature = new ol.Feature({ geometry: route });

        const vectorLayer = new ol.layer.Vector({
            source: new ol.source.Vector({ features: [routeFeature] }),
            style: new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: '#4CAF50',
                    width: 4
                })
            })
        });

        const map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({ source: new ol.source.OSM() }),
                vectorLayer
            ],
            view: new ol.View({
                center: coordinates[coordinates.length - 1],
                zoom: 16
            })
        });
    });
</script>
