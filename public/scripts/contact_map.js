// -------------------------------------------------
// Contact map initialization and responsive behavior
// -------------------------------------------------

/**
 * Initializes the contact map after the DOM is fully loaded.
 */
document.addEventListener('DOMContentLoaded', () => {

    // Check whether the map container exists on the current page.
    const mapContainer = document.getElementById('map');

    if (!mapContainer) {
        return;
    }

    /**
     * Geographic coordinates of "Le Parc des Anes".
     */
    const latitude = 43.567;
    const longitude = 5.367;

    /**
     * Returns the appropriate map zoom level based on the viewport width.
     *
     * @returns {number} Responsive zoom level.
     */
    function getResponsiveZoom() {
        const width = window.innerWidth;

        if (width < 480) return 13;
        if (width < 768) return 14;
        return 15;
    }

    /**
     * Initializes the Leaflet map with keyboard navigation and
     * scroll-wheel zoom enabled.
     */
    const map = L.map(mapContainer, {
        keyboard: true,
        scrollWheelZoom: true,
        zoomControl: false
    }).setView([latitude, longitude], 15);

    // Add the OpenStreetMap base layer.
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Add zoom controls in the bottom-right corner.
    L.control.zoom({
        position: "bottomright"
    }).addTo(map);

    /**
     * Creates the map marker and displays the location popup.
     */
    const marker = L.marker([latitude, longitude], {
        icon: L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        })
    }).addTo(map)
        .bindPopup("<strong>Le Parc des Anes</strong><br>Chemin de Saint-François<br>13510 Éguilles", {
            maxWidth: 250,
            keepInView: true,
            autoPan: true
        })
        .openPopup();

    // Adapt the map size and zoom level when the viewport is resized.
    window.addEventListener('resize', () => {
        map.invalidateSize();
        map.setZoom(getResponsiveZoom());
    });
});