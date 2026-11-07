import Alpine from 'alpinejs';
import L from 'leaflet';

import iconRetinaUrl from 'leaflet/dist/images/marker-icon-2x.png';
import iconUrl from 'leaflet/dist/images/marker-icon.png';
import shadowUrl from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl,
    iconUrl,
    shadowUrl,
});

window.Alpine = Alpine;
Alpine.start();

const DHAKA = [23.8103, 90.4125];

const demoStops = [
    { name: 'Hub — Gulshan', lat: 23.7925, lng: 90.4078 },
    { name: 'Stop 1 — Banani', lat: 23.7949, lng: 90.4063 },
    { name: 'Stop 2 — Mohakhali', lat: 23.7778, lng: 90.4056 },
    { name: 'Stop 3 — Tejgaon', lat: 23.7662, lng: 90.3997 },
    { name: 'Stop 4 — Dhanmondi', lat: 23.7461, lng: 90.3742 },
];

function initMap(el, { compact = false } = {}) {
    if (! el) {
        return;
    }

    const map = L.map(el, {
        scrollWheelZoom: ! compact,
        zoomControl: ! compact,
    }).setView(DHAKA, compact ? 12 : 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    const route = demoStops.map((s) => [s.lat, s.lng]);
    L.polyline(route, {
        color: getComputedStyle(document.documentElement).getPropertyValue('--lmdo-teal-deep').trim() || '#0f766e',
        weight: 4,
        opacity: 0.85,
    }).addTo(map);

    demoStops.forEach((stop, i) => {
        L.marker([stop.lat, stop.lng])
            .addTo(map)
            .bindPopup(`<strong>${stop.name}</strong>${i === 0 ? ' (depot)' : ''}`);
    });

    map.fitBounds(route, { padding: compact ? [24, 24] : [48, 48] });

    setTimeout(() => map.invalidateSize(), 200);
}

document.addEventListener('DOMContentLoaded', () => {
    const hero = document.getElementById('lmdo-hero-map');
    const demo = document.getElementById('lmdo-demo-map');

    if (hero) {
        initMap(hero, { compact: true });
    }

    if (demo) {
        initMap(demo, { compact: false });
    }
});
