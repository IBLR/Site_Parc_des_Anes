<?php
$completed = $completed ?? false;
$isAuthenticated = $isAuthenticated ?? false;
?>

<section class="layout-section text-center">

        <?php if (!empty($success)): ?>
            <p
                class="success-message donation-message"
                data-donation-success="true"
            >
                <?= htmlspecialchars($success) ?>
            </p>
        <?php endif; ?>


    <?php if (!$completed): ?>
    <div>
        <p class="high-text">
            Merci pour votre soutien au Parc des Anes.
        </p>
        <p class="high-text">
            Renseignez vos coordonnées pour finaliser votre don.
        </p>
    </div>

    <div>
        <div class="donation-amount-summary">
            <p>
                Montant de votre don :
                <strong>
                    <?= number_format((float) $amount, 2, ',', ' ') ?> €
                </strong>
            </p>
        </div>

        <div class="donation-change-amount">
            <a href="<?= BASE_URL ?>/?url=don#anchor-donation-choice">
                ← Modifier le montant de mon don
            </a>
        </div>
    </div>

    <div
        data-user-authenticated="<?= !empty($isAuthenticated) ? 'true' : 'false' ?>"
        data-user-admin="<?= !empty($_SESSION['user']['isAdmin']) ? 'true' : 'false' ?>"
        hidden
    ></div>
    <form
        action="<?= BASE_URL ?>/?url=don/send"
        method="post"
        novalidate
        class="donation-form-check donation-form-container"
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
        >


        <div class="form-row-subcontainer">

            <div class="form-field-block input-style">

                <label
                    for="last-name"
                    class="high-text"
                >
                    Nom
                    <span
                        class="required-field"
                        aria-hidden="true"
                    >
                        *
                    </span>
                </label>

                <input
                    type="text"
                    id="last-name"
                    name="last_name"
                    required
                    value="<?= htmlspecialchars($old['last_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="error-last-name"
                    placeholder="Ex : DUPONT"
                    autocomplete="family-name"
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

                <label
                    for="first-name"
                    class="high-text"
                >
                    Prénom
                    <span
                        class="required-field"
                        aria-hidden="true"
                    >
                        *
                    </span>
                </label>

                <input
                    type="text"
                    id="first-name"
                    name="first_name"
                    required
                    value="<?= htmlspecialchars($old['first_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="error-first-name"
                    placeholder="Ex : Marie"
                    autocomplete="given-name"
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

            <?php if (!empty($isAuthenticated)): ?>

                <div class="form-field-block input-style">

                    <label
                        for="email"
                        class="high-text"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        readonly
                        aria-describedby="error-email"
                        autocomplete="email"
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

            <?php endif; ?>


            <div class="form-field-block input-style">

                <label
                    for="phone"
                    class="high-text"
                >
                    Téléphone
                </label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    value="<?= htmlspecialchars($old['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    aria-describedby="error-phone"
                    placeholder="Ex : 06 12 34 56 78"
                    autocomplete="tel"
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

                <label for="comment" class="high-text">
                    Commentaire
                </label>

                <textarea
                    id="comment"
                    name="comment"
                    rows="4"
                    placeholder="Ex : Je souhaite recevoir un reçu fiscal."
                ><?= htmlspecialchars($old['comment'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

                <span
                    class="error-message"
                    id="error-comment"
                    role="alert"
                    aria-live="assertive"
                >
                    <?= htmlspecialchars($errors['comment'] ?? '') ?>
                </span>
            </div>
        </div>


        <div class="form-row-subcontainer">

            <?php if (!empty($errors['csrf'])): ?>

                <p
                    class="error-message"
                    role="alert"
                    aria-live="assertive"
                >
                    <?= htmlspecialchars($errors['csrf'], ENT_QUOTES, 'UTF-8') ?>
                </p>

            <?php endif; ?>


            <?php if (!empty($errors['database'])): ?>

                <p
                    class="error-message"
                    role="alert"
                    aria-live="assertive"
                >
                    <?= htmlspecialchars($errors['database'], ENT_QUOTES, 'UTF-8') ?>
                </p>

            <?php endif; ?>

        </div>


        <p
            class="error-message donation-message"
            id="donation-login-required"
            hidden
        ></p>

        <p
            class="donation-payment-processing"
            id="donation-payment-processing"
            hidden
        ></p>


        <div class="form-row-subcontainer">

            <button
                type="submit"
                class="btn btn--primary btn--square-lg"
            >
                Valider mon don
            </button>

        </div>

    </form>



    <div class="contribution-secured">

        <img
            src="<?= BASE_URL ?>/assets/icons/pages/donation/secured.svg"
            alt=""
        >

        <p>
            Paiement sécurisé -
            Reçu fiscal disponible sur demande
        </p>

    </div>
    <?php endif; ?>
</section>

