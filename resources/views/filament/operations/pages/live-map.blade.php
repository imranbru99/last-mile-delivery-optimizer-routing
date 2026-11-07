<x-filament-panels::page>
    <div
        wire:poll.10s
        x-data="liveMap(@js($this->markers))"
        x-init="init()"
        class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700"
    >
        <div x-ref="map" class="h-[70vh] w-full"></div>
    </div>

    @assets
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endassets

    @script
    <script>
        Alpine.data('liveMap', (markers) => ({
            map: null,
            layer: null,
            markers,
            init() {
                this.map = L.map(this.$refs.map).setView([23.8103, 90.4125], 12)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap'
                }).addTo(this.map)
                this.layer = L.layerGroup().addTo(this.map)
                this.render()
                this.$watch('markers', () => this.render())
            },
            render() {
                if (!this.layer) return
                this.layer.clearLayers()
                const bounds = []
                ;(this.markers || []).forEach((m) => {
                    const marker = L.marker([m.lat, m.lng]).bindPopup(`${m.driver}<br>${m.speed} km/h`)
                    marker.addTo(this.layer)
                    bounds.push([m.lat, m.lng])
                })
                if (bounds.length) this.map.fitBounds(bounds, { padding: [24, 24] })
            }
        }))
    </script>
    @endscript
</x-filament-panels::page>
