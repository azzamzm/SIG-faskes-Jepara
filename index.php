<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG Faskes Jepara - Dijkstra</title>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    
    <style>
        body { margin: 0; padding: 0; font-family: Arial, sans-serif; display: flex; flex-direction: column; height: 100vh; }
        #header { padding: 15px; background: #2c3e50; color: white; text-align: center; }
        #controls { padding: 10px; background: #ecf0f1; display: flex; flex-direction: column; align-items: center; gap: 5px; }
        #map { flex-grow: 1; width: 100%; }
        select { padding: 8px; border-radius: 4px; border: 1px solid #bdc3c7; width: 250px; }
        #result-info { font-weight: bold; color: #2c3e50; margin-top: 5px; }
    </style>
</head>
<body>

    <div id="header">
        <h2 style="margin:0;">SIG Fasilitas Kesehatan Kabupaten Jepara</h2>
    </div>

    <div id="controls">
        <div>
            <label>Pilih Tujuan:</label>
            <select id="faskes-select">
                <option value="">-- Pilih Fasilitas Kesehatan --</option>
            </select>
        </div>
        <div id="result-info">Jarak Terpendek: - | Estimasi: -</div>
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

    <script>
        // 1. Inisialisasi Peta (Default Pusat: Jepara)
        var map = L.map('map').setView([-6.593, 110.675], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // 2. Variabel Titik Awal (Default: Alun-alun Jepara jika GPS ditolak)
        var userCoords = L.latLng(-6.5888, 110.6686);

        // 3. Konfigurasi Routing Machine (Dijkstra Engine)
        var control = L.Routing.control({
            waypoints: [userCoords], 
            routeWhileDragging: true,
            showAlternatives: true,
            createMarker: function() { return null; } // Menghilangkan marker default routing
        }).addTo(map);

        // 4. FITUR GEOLOCATION (Mencari Lokasi User)
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                userCoords = L.latLng(position.coords.latitude, position.coords.longitude);
                
                // Tambahkan Marker Khusus untuk Lokasi User (Warna Merah)
                L.marker(userCoords, {
                    icon: L.icon({
                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                        iconSize: [25, 41],
                        iconAnchor: [12, 41],
                        popupAnchor: [1, -34],
                        shadowSize: [41, 41]
                    })
                }).addTo(map).bindPopup("Lokasi Anda Saat Ini").openPopup();

                // Set titik awal routing ke lokasi user
                control.spliceWaypoints(0, 1, userCoords);
                map.setView(userCoords, 14);
            }, function() {
                alert("Akses lokasi ditolak. Menggunakan lokasi default (Alun-alun).");
            });
        }

        // 5. Ambil Data dari Database dan Tambahkan ke Dropdown & Marker
        const select = document.getElementById('faskes-select');

        fetch('get_data.php')
            .then(response => response.json())
            .then(data => {
                data.forEach(loc => {
                    // Marker Faskes
                    var marker = L.marker([loc.lat, loc.lng]).addTo(map);
                    marker.bindPopup("<b>" + loc.nama + "</b><br>" + loc.tipe);

                    // Dropdown Option
                    let option = document.createElement('option');
                    option.value = JSON.stringify({lat: loc.lat, lng: loc.lng});
                    option.text = loc.nama;
                    select.appendChild(option);

                    // Event Klik Marker
                    marker.on('click', function() {
                        updateRoute(loc.lat, loc.lng);
                    });
                });
            })
            .catch(error => console.error('Gagal memuat data:', error));

        // 6. Fungsi Update Rute
        function updateRoute(destLat, destLng) {
            control.setWaypoints([
                userCoords,
                L.latLng(destLat, destLng)
            ]);
            map.flyTo([destLat, destLng], 14);
        }

        // 7. Event Listener Dropdown
        select.addEventListener('change', function() {
            if (this.value) {
                const coords = JSON.parse(this.value);
                updateRoute(coords.lat, coords.lng);
            }
        });

        // 8. Menampilkan Info Jarak dan Waktu (Hasil Dijkstra)
        control.on('routesfound', function(e) {
            var routes = e.routes;
            var summary = routes[0].summary;
            var distance = (summary.totalDistance / 1000).toFixed(2);
            var time = Math.round(summary.totalTime / 60);
            document.getElementById('result-info').innerHTML = 
                "Jarak Terpendek: " + distance + " Km | Estimasi: " + time + " Menit";
        });
    </script>
</body>
</html>