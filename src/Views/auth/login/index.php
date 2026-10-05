<main class="content layout-section auth-section">

    <h1>Connexion</h1>

    <?php if (!empty($error)): ?>
        <p
            class="error-message auth-error"
            role="alert"
            aria-live="assertive"
        >
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <?php
    $sections = [
        'login_form',
    ];

    foreach ($sections as $section) {
        $this->renderSection(__DIR__, $section, get_defined_vars());
    }
    ?>

    <p class="auth-switch">
        Pas encore de compte ?
        <a href="<?= BASE_URL ?>/?url=inscription">
            Créer un compte
        </a>
    </p>

</main>