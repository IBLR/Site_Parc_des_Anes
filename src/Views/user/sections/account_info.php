<section class="layout-section account-section account-info">

    <h1>Mon compte</h1>

    <p>
        Bonjour, vous êtes connecté en tant que :
        <strong>
            <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>
        </strong>
    </p>

    <?php if (!empty($canAccessAdmin)) : ?>

        <div class="account-admin-action">

            <a
                href="<?= BASE_URL ?>/?url=admin"
                class="account-dashboard-link"
            >
                Accéder au TABLEAU DE BORD
            </a>

        </div>

    <?php endif; ?>

    <div class="account-logout-action">
        <div>
            <a href="<?= BASE_URL ?>/?url=deconnexion"
            class="logout-icon-link high-text"
            aria-label="Déconnexion">
                <span class="logout-icon" aria-hidden="true">
                    <?= file_get_contents(PUBLIC_PATH . '/assets/icons/ui/icon-logout.svg') ?>
                </span>
                Déconnexion
            </a>
        </div>
    </div>

</section>