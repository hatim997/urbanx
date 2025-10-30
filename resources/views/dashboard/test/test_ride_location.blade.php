@extends('layouts.master')

@section('title', 'Location Test')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .search-box {
        z-index: 1000;
        background: white;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .suggestions {
        background: white;
        border: 1px solid #ccc;
        border-top: none;
        max-height: 150px;
        overflow-y: auto;
        position: absolute;
        width: 100%;
        z-index: 2000;
    }
    .suggestion-item {
        padding: 8px;
        cursor: pointer;
    }
    .suggestion-item:hover {
        background: #f1f1f1;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="search-box mb-5">
        <input type="text" id="pickup" class="form-control mb-2" placeholder="Enter Pickup Location">
        <div id="pickupSuggestions" class="suggestions"></div>
        <input type="text" id="drop" class="form-control" placeholder="Enter Drop Location">
        <div id="dropSuggestions" class="suggestions"></div>
        <button id="getRouteBtn" class="btn btn-primary w-100 mt-2">Get Route</button>
    </div>

    <div id="map" style="height:100vh;"></div>
    <div id="info" style="background:white;padding:10px;border-radius:10px;z-index:1000;">
        Waiting for input...
    </div>
</div>
@endsection

@section('script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map = L.map('map').setView([31.5204, 74.3587], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let pickupMarker, dropMarker;
let pickupCoords = null, dropCoords = null;

function setupAutocomplete(inputId, suggestionId, callback) {
    const input = document.getElementById(inputId);
    const suggestionBox = document.getElementById(suggestionId);

    input.addEventListener('input', async () => {
        const query = input.value.trim();
        suggestionBox.innerHTML = '';
        if (query.length < 3) return;

        // ✅ Restrict search to Pakistan only
        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&countrycodes=pk&q=${query}&limit=5`);
        const data = await res.json();

        data.forEach(item => {
            const div = document.createElement('div');
            div.className = 'suggestion-item';
            div.innerText = item.display_name;
            div.onclick = () => {
                input.value = item.display_name;
                suggestionBox.innerHTML = '';
                callback([parseFloat(item.lat), parseFloat(item.lon)]);
            };
            suggestionBox.appendChild(div);
        });
    });
}


setupAutocomplete('pickup', 'pickupSuggestions', coords => {
    pickupCoords = coords;
    if (pickupMarker) map.removeLayer(pickupMarker);
    pickupMarker = L.marker(coords).addTo(map).bindPopup('Pickup').openPopup();
    map.setView(coords, 13);
});

setupAutocomplete('drop', 'dropSuggestions', coords => {
    dropCoords = coords;
    if (dropMarker) map.removeLayer(dropMarker);
    dropMarker = L.marker(coords).addTo(map).bindPopup('Drop').openPopup();
    map.setView(coords, 13);
});

document.getElementById('getRouteBtn').addEventListener('click', () => {
    if (!pickupCoords || !dropCoords) {
        alert('Please select both pickup and drop locations.');
        return;
    }

    fetch(`/api/ride/route?pickup_lat=${pickupCoords[0]}&pickup_lng=${pickupCoords[1]}&drop_lat=${dropCoords[0]}&drop_lng=${dropCoords[1]}&vehicle_type_id=1`)
        .then(res => res.json())
        .then(data => {
            console.log(data.coordinates)
            const routeCoords = data.coordinates.map(c => [c[1], c[0]]);
            L.polyline(routeCoords, { color: 'blue', weight: 5 }).addTo(map);
            map.fitBounds(L.polyline(routeCoords).getBounds());

            document.getElementById('info').innerHTML = `
                Distance: ${data.distance_km} km<br>
                Duration: ${data.duration_min} min<br>
                Fare: Rs. ${data.estimated_fare}
            `;
        });
});
</script>
@endsection
