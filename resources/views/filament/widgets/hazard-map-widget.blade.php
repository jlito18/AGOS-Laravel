<x-filament-widgets::widget>
    <x-filament::section>
        <h2 class="text-lg font-bold mb-2">📍 A.G.O.S. Live Hazard Map</h2>

        <div id="evac-data" data-centers="{{ json_encode($evacCenters) }}" style="display: none;"></div>

        <div wire:ignore>
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

            <div x-data="{
                    map: null,
                    // Initialize PHP variables into JavaScript
                    sensorLat: parseFloat('{{ $sensorLat }}'),
                    sensorLng: parseFloat('{{ $sensorLng }}'),
                    hazardRadius: parseInt('{{ $hazardRadius }}'),

                    init() {
                        if (typeof L === 'undefined') {
                            const script = document.createElement('script');
                            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                            script.onload = () => { this.drawMap(); }; 
                            document.head.appendChild(script);
                        } else {
                            this.drawMap();
                        }
                    },
                    drawMap() {
                        if (this.map !== null) return;

                        // Center the map on the main sensor's coordinates
                        this.map = L.map(this.$refs.mapContainer).setView([this.sensorLat, this.sensorLng], 15);
                        
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '© OpenStreetMap'
                        }).addTo(this.map);
                        
                        // Evaluate current water level against thresholds
                        let waterLvl = parseFloat('{{ $currentWaterLevel }}');
                        let warningLvl = parseFloat('{{ $warningLevel }}');
                        let dangerLvl = parseFloat('{{ $dangerLevel }}');

                        // Dynamic Color Logic based on Alert Level
                        let zoneColor = '#22c55e'; // Green (Normal/Safe)
                        let statusText = 'NORMAL (Safe)';

                        if (waterLvl >= dangerLvl) {
                            zoneColor = '#ef4444'; // Red (Danger)
                            statusText = 'CRITICAL DANGER (Evacuate)';
                        } else if (waterLvl >= warningLvl) {
                            zoneColor = '#f97316'; // Orange (Warning)
                            statusText = 'WARNING (Prepare to Evacuate)';
                        }

                        // Draw the defined hazard zone radius
                        L.circle([this.sensorLat, this.sensorLng], {
                            color: zoneColor,
                            fillColor: zoneColor,
                            fillOpacity: 0.35,
                            radius: this.hazardRadius
                        }).addTo(this.map).bindPopup('<b>Flood Status: ' + statusText + '</b><br>Hazard Radius: ' + this.hazardRadius + ' meters');

                        // Main Sensor Marker
                        L.circleMarker([this.sensorLat, this.sensorLng], {
                            color: '#b91c1c', 
                            fillColor: '#ef4444', 
                            fillOpacity: 0.9,
                            radius: 10,
                            weight: 2
                        }).addTo(this.map)
                          .bindPopup('<b>A.G.O.S. Main Sensor</b><br>Hardware Location').openPopup();
                        
                        // Evacuation Center Markers
                        let rawData = document.getElementById('evac-data').getAttribute('data-centers');
                        let centers = JSON.parse(rawData);

                        centers.forEach(center => {
                            if(center.latitude && center.longitude) {
                                let lat = parseFloat(center.latitude);
                                let lng = parseFloat(center.longitude);

                                L.circleMarker([lat, lng], {
                                    color: '#15803d', 
                                    fillColor: '#22c55e', 
                                    fillOpacity: 0.9,
                                    radius: 10,
                                    weight: 2
                                }).addTo(this.map)
                                  .bindPopup('<b>' + center.name + '</b><br>Status: ' + center.status);
                            }
                        });

                        // Refresh map size to prevent rendering glitches
                        setTimeout(() => { this.map.invalidateSize(); }, 500);
                    }
                }">
                <div x-ref="mapContainer" style="height: 480px; width: 100%; border-radius: 10px; z-index: 1;"></div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>