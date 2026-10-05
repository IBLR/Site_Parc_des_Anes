<header class="admin-header">

    <div class="admin-header-inner">

        <h1 class="admin-h1">
            Administration
        </h1>

        <nav class="admin-header-nav" aria-label="Navigation administration">
            <div>
                <a
                    class="admin-header-site-link"
                    href="<?= BASE_URL ?>/?url=accueil"
                >
                    Retour au site
                </a>
            </div>
            <div>
                <a href="<?= BASE_URL ?>/?url=deconnexion"
                    class="header-icon-link"
                    aria-label="Déconnexion"
                    title="Déconnexion"
                >
                    <span class="header-icon" aria-hidden="true">
                        <?= file_get_contents(PUBLIC_PATH . '/assets/icons/ui/icon-logout.svg') ?>
                    </span>
                </a>
            </div>

        </nav>

    </div>

</header>