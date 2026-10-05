<header class="header">
    <div class="header-container">

        <?php // ================================================== ?>
        <?php // Brand ?>
        <?php // ================================================== ?>

        <div class="logo">
            <a href="<?= BASE_URL ?>/?url=accueil">
                <img src="<?= BASE_URL ?>/assets/img/branding/logo-main.jpg"
                    alt="Logo du site Le Parc des Anes, page d'accueil">
            </a>
        </div>


        <?php // ================================================== ?>
        <?php // Primary navigation ?>
        <?php // ================================================== ?>

        <nav class="header-nav" id="primary-navigation" aria-label="Navigation principale">
            <ul role="list" class="high-text">
                <li>
                    <a href="<?= BASE_URL ?>/?url=accueil">Accueil</a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/?url=nos-anes">Nos ânes</a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/?url=boutique">Boutique</a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/?url=contact">Accès &amp; Contact</a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/?url=don" class="btn btn--primary btn--circle-md">Faire un don</a>
                </li>
                <li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="<?= BASE_URL ?>/?url=compte" class="header-icon-link" aria-label="Compte" title="Compte">
                            <span class="header-icon" aria-hidden="true">
                                <?= file_get_contents(PUBLIC_PATH . '/assets/icons/ui/icon-user-account.svg') ?>
                            </span>
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/?url=connexion" class="header-icon-link" aria-label="Connexion" title="Connexion">
                            <span class="header-icon" aria-hidden="true">
                                <?= file_get_contents(PUBLIC_PATH . '/assets/icons/ui/icon-login.svg') ?>
                            </span>
                        </a>
                    <?php endif; ?>
                </li>

            </ul>
        </nav>


        <?php // ================================================== ?>
        <?php // Mobile menu button ?>
        <?php // ================================================== ?>
    
        <div class="header-nav-toggle-btn">
            <button aria-controls="primary-navigation" aria-expanded="false" aria-label="Ouvrir le menu principal">
            <img src="<?= BASE_URL ?>/assets/icons/ui/icon-burger.svg" alt="">
            </button>
        </div>

    </div>
</header>