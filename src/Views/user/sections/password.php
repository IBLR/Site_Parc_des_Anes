<section class="layout-section account-section">

    <h2 id="anchor-account-password">Modifier mon mot de passe</h2>

    <div>
        <?php if (!empty($error)): ?>

            <p
                class="error-message"
                role="alert"
                aria-live="assertive"
            >
                <?= htmlspecialchars(
                    $error,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

        <?php endif; ?>


        <?php if (!empty($success)): ?>

            <p
                id="account-password-success"
                class="success-message"
                role="status"
                aria-live="polite"
            >
                <?= htmlspecialchars(
                    $success,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

        <?php endif; ?>
    </div>

    <form
        method="POST"
        action="<?= BASE_URL ?>/?url=compte/update-password"
        class="account-password-form"
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $csrfToken,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <div class="form-field-block input-style">

            <label
                for="current-password"
                class="high-text"
            >
                Mot de passe actuel
                <span
                    class="required-field"
                    aria-hidden="true"
                >*</span>
            </label>

            <input
                type="password"
                id="current-password"
                name="current_password"
                autocomplete="current-password"
                required
            >

        </div>


        <div class="form-field-block input-style">

            <label
                for="new-password"
                class="high-text"
            >
                Nouveau mot de passe
                <span
                    class="required-field"
                    aria-hidden="true"
                >*</span>
            </label>

            <input
                type="password"
                id="new-password"
                name="new_password"
                autocomplete="new-password"
                required
            >

        </div>


        <div class="form-field-block input-style">

            <label
                for="confirm-password"
                class="high-text"
            >
                Confirmer le nouveau mot de passe
                <span
                    class="required-field"
                    aria-hidden="true"
                >*</span>
            </label>

            <input
                type="password"
                id="confirm-password"
                name="confirm_password"
                autocomplete="new-password"
                required
            >

        </div>


        <button type="submit" class="btn btn--primary btn--square-lg">
            Modifier le mot de passe
        </button>

    </form>

</section>