<section id="anchor-access" class="layout-section text-center">
    <h2>Comment accéder au parc</h2>
    <div class="page-contact-description">
        <p>
            Le Parc des Anes est situé à Éguilles, dans le Sud de la France,
            facilement accessible en voiture ou en transports en commun.
        </p>
        <p>
            Utilisez notre carte interactive pour planifier votre itinéraire
            et préparer votre visite en toute sérénité.
        </p>
    </div>
    <div>
        <?php // Hidden link for accessibility ?>
        <a href="#after-map" class="skip-map">
            Passer la carte interactive
        </a>
        <span id="map-help" hidden>
            Utilisez les boutons plus et moins pour zoomer, et les flèches du
            clavier pour vous déplacer.
        </span>

        <?php // Interactive map (script contact_map.js) ?>
        <div
            id="map"
            role="region"
            aria-label="Carte interactive de localisation"
            aria-describedby="map-help"
            tabindex="0">
        </div>

        <?php // Target of hidden link for accessibility ?>
        <div id="after-map"></div>
    </div>
</section>