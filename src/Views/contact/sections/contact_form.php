<section class="layout-section text-center">
    <div class="page-contact-description">
        <p>
            Vous pouvez également nous envoyer un message directement via ce formulaire.
        </p>
        <p>
            Nous répondrons rapidement pour vous aider à préparer votre visite ou répondre
            à toutes vos demandes.
        </p>
    </div>

    <div>
        <?php if (!empty($success)): ?>
            <p id="anchor-contact-success" class="success-message contact-success-message">
                <?= htmlspecialchars($success) ?>
            </p>
        <?php endif; ?>
    </div>

    <form action="<?= BASE_URL ?>/?url=contact/send"
        method="post"
        id="anchor-contact-form"
        novalidate
        class="contact-form-check contact-form-container"
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
        >

        <div class="form-row-subcontainer">
            <div class="form-field-block input-style">
                <label for="last-name" class="high-text">Nom <span class="required-field" aria-hidden="true">*</span></label>
                <input
                    type="text"
                    id="last-name"
                    name="last_name"
                    required
                    value="<?= htmlspecialchars($old['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="error-last-name"
                    placeholder="Ex : DUPONT"
                >
                <span
                    class="error-message"
                    id="error-last-name"
                    role="alert"
                    aria-live="assertive"
                >
                    <?= htmlspecialchars($errors['last_name'] ?? '') ?>
                </span>
            </div>
            <div class="form-field-block input-style">
                <label for="first-name" class="high-text">Prénom <span class="required-field" aria-hidden="true">*</span></label>
                <input
                    type="text"
                    id="first-name"
                    name="first_name"
                    autocomplete="given-name"
                    required
                    value="<?= htmlspecialchars($old['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="error-first-name"
                    placeholder="Ex : Marie"
                >
                <span
                    class="error-message"
                    id="error-first-name"
                    role="alert"
                    aria-live="assertive"
                >
                    <?= htmlspecialchars($errors['first_name'] ?? '') ?>
                </span>
            </div>
        </div>
        <div class="form-row-subcontainer">
            <div class="form-field-block input-style">
                <label for="email" class="high-text">Email <span class="required-field" aria-hidden="true">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    required
                    value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="error-email"
                    placeholder="Ex : marie.dupont@email.fr"
                >
                <span
                    class="error-message"
                    id="error-email"
                    role="alert"
                    aria-live="assertive"
                >
                    <?= htmlspecialchars($errors['email'] ?? '') ?>
                </span>
            </div>
            <div class="form-field-block input-style">
                <label for="phone" class="high-text">Téléphone </label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    autocomplete="tel"
                    value="<?= htmlspecialchars($old['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="error-phone"
                    placeholder="Ex : 06 12 34 56 78"
                >
                <span
                    class="error-message"
                    id="error-phone"
                    role="alert"
                    aria-live="assertive"
                >
                    <?= htmlspecialchars($errors['phone'] ?? '') ?>
                </span>
            </div>
        </div>
        <div class="form-row-subcontainer">
            <div class="form-field-block textarea-style">
                <label for="message" class="high-text">Message <span class="required-field" aria-hidden="true">*</span></label>
                <textarea
                    id="message"
                    name="message"
                    rows="4"
                    required
                    aria-describedby="error-message"
                    placeholder="Décrivez-nous votre demande"
                ><?= htmlspecialchars($old['message'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                <span
                    class="error-message"
                    id="error-message"
                    role="alert"
                    aria-live="assertive"
                >
                    <?= htmlspecialchars($errors['message'] ?? '') ?>
                </span>
            </div>
        </div>
        <div class="form-row-subcontainer">
            <?php if (!empty($errors['csrf'])): ?>
                <p class="error-message">
                    <?= htmlspecialchars($errors['csrf']) ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="form-row-subcontainer">
            <button type="submit" class="btn btn--primary btn--square-lg">Envoyer</button>
        </div>
    </form>
</section>

