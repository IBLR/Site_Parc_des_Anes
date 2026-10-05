<?php // Visual separation ?>
<div class="section-space"></div>


<footer class="footer">
    <div class="footer-container">
        <?php // ================================================== ?>
        <?php // Upper part ?>
        <?php // ================================================== ?>

        <div class="footer-top">

            <?php // ================================================== ?>
            <?php // Left block / brand ?>
            <?php // ================================================== ?>

            <div class="footer-brand">

                <div class="logo">
                    <a href="<?= BASE_URL ?>/?url=accueil">
                        <img src="<?= BASE_URL ?>/assets/img/branding/logo-main.jpg" alt="Logo du site Le Parc des Anes, page d'accueil">
                    </a>
                </div>


                <div class="brand-description">
                    <p>
                        Le Parc des Anes, un lieu de découverte et de partage avec les animaux et la nature.
                    </p>
                </div>


                <nav aria-label="Réseaux sociaux">
                    <ul role="list" class="social-networks">
                    <li>
                        <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer">
                            <img src="<?= BASE_URL ?>/assets/icons/social/icon-instagram.svg" alt="Instagram">
                        </a>
                    </li>
                    <li>
                        <a href="https://x.com" target="_blank" rel="noopener noreferrer">
                            <img src="<?= BASE_URL ?>/assets/icons/social/icon-X.svg" alt="X, anciennement Twitter">
                        </a>
                    </li>
                    <li>
                        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer">
                            <img src="<?= BASE_URL ?>/assets/icons/social/icon-facebook.svg" alt="Facebook">
                        </a>
                    </li>
                    <li>
                        <a href="https://fr.pinterest.com/" target="_blank" rel="noopener noreferrer">
                            <img src="<?= BASE_URL ?>/assets/icons/social/icon-pinterest.svg" alt="Pinterest">
                        </a>
                    </li>
                    </ul>
                </nav>
            </div>


            <?php // ================================================== ?>
            <?php // Right block / secondary navigation ?>
            <?php // ================================================== ?>

            <nav aria-label="Navigation secondaire">
                <ul role="list" class="secondary-navigation">
                    <li role="list" class="sub-nav">
                        <h2 class="sub-nav-title">Le parc</h2>
                        <ul role="list">
                            <li>
                                <a href="<?= BASE_URL ?>/?url=accueil#anchor-story">Notre histoire</a>
                            </li>
                            <li>
                                <a href="<?= BASE_URL ?>/?url=accueil#anchor-mission">Nos missions</a>
                            </li>
                            <li>
                                <a href="<?= BASE_URL ?>/?url=nos-anes#anchor-donkey">Nos ânes</a>
                            </li>
                        </ul>
                    </li>


                    <li role="list" class="sub-nav">
                        <h2 class="sub-nav-title">Accès &amp; Contact</h2>
                        <ul role="list">
                            <li>
                                <a href="<?= BASE_URL ?>/?url=contact#anchor-access">Accéder au parc</a>
                            </li>
                            <li>
                                <a href="<?= BASE_URL ?>/?url=contact#anchor-schedule-price">Horaires &amp; Tarifs</a>
                            </li>
                            <li>
                                <a href="<?= BASE_URL ?>/?url=contact#anchor-contact">Nous contacter</a>
                            </li>
                        </ul>
                    </li>


                    <li role="list" class="sub-nav">
                        <h2 class="sub-nav-title">Boutique &amp; Don</h2>
                        <ul role="list">
                            <li>
                                <a href="<?= BASE_URL ?>/?url=boutique#anchor-shop">Nos produits artisanaux</a>
                            </li>
                            <li>
                                <a href="<?= BASE_URL ?>/?url=don#anchor-donation">Nous soutenir</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>



        <?php // ================================================== ?>
        <?php // Lower part ?>
        <?php // ================================================== ?>

        <div class="footer-bottom">

            <div class="copyright">
                <p>© 2026 Le Parc des Anes. Tous droits réservés.</p>
            </div>

            <nav aria-label="Liens légaux">
                <ul role="list" class="legal">
                    <li>
                        <a href="<?= BASE_URL ?>/?url=mentions-legales">Mentions légales</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/?url=politique-confidentialite">Politique de confidentialité</a>
                    </li>
                    <li>
                        <a href="<?= BASE_URL ?>/?url=cgv">CGV</a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="demo-notice">
            <p>
                SITE FICTIF - Projet de démonstration de compétences réalisé par Isabelle BAILLY LE ROCH
                <br>Pour toute question relative au projet ou aux données enregistrées :
                <a href="mailto:blr.isabelle@gmail.com">blr.isabelle@gmail.com</a>
            </p>
        </div>
    </div>
</footer>