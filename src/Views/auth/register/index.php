<main class="content layout-section auth-section">

    <h1>Inscription</h1>

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
        'register_form',
    ];

    foreach ($sections as $section) {
        $this->renderSection(__DIR__, $section, get_defined_vars());
    }
    ?>

    <p class="auth-switch">
        Déjà un compte ?
        <a href="<?= BASE_URL ?>/?url=connexion">
            Se connecter
        </a>
    </p>

</main>