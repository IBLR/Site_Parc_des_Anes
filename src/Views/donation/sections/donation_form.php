<section class="layout-section text-center">
    <div class="contribution-form-container" id="anchor-donation-choice">
    <h2>Choisissez votre contribution</h2>

    <form
        action="<?= BASE_URL ?>/?url=don/checkout"
        method="post"
        class="donation-amount-form"
        novalidate
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrfToken) ?>"
        >

        <fieldset>
        <legend class="visually-hidden">Choisissez le montant de votre don</legend>

        <div class="form-fixed-amounts-container">
            <label class="fixed-amount-btn-radio">
            <input type="radio" name="donation_choice" value="10" tabindex="-1">
            <span class="fixed-amount-card high-text">10 €</span>
            </label>

            <label class="fixed-amount-btn-radio">
            <input type="radio" name="donation_choice" value="25" tabindex="-1">
            <span class="fixed-amount-card high-text">25 €</span>
            </label>

            <label class="fixed-amount-btn-radio">
            <input type="radio" name="donation_choice" value="50" tabindex="-1">
            <span class="fixed-amount-card high-text">50 €</span>
            </label>
            
            <label class="fixed-amount-btn-radio">
            <input type="radio" name="donation_choice" value="100" tabindex="-1">
            <span class="fixed-amount-card high-text">100 €</span>
            </label>
        </div>

        <div class="form-other-amount-container">

            <label class="other-amount-btn-radio">
            <input type="radio" name="donation_choice" value="other" tabindex="-1">
            <span>Montant libre :</span>
            <span class="visually-hidden">en euros</span>
            </label>

            <div class="other-amount-card">
            <input
                type="number"
                id="other-amount-input"
                min="1"
                step="1"
                inputmode="numeric"
                placeholder="-"
            />
            <span aria-hidden="true" class="high-text">€</span>
            </div>
        </div>

        <input
            type="hidden"
            id="donation-amount-final"
            name="donation_amount"
            required
        />
        </fieldset>
        <p id="donation-amount-error" class="error-message" hidden aria-live="assertive">
        Merci de choisir le montant de votre don.
        </p>
        <button type="submit" id="submit-btn" class="btn btn--primary btn--square-lg">Je fais un don</button>
    </form> 
    <div class="contribution-secured">
        <img src="<?= BASE_URL ?>/assets/icons/pages/donation/secured.svg" alt="">
        <p>Paiement sécurisé  -  Reçu fiscal disponible sur demande</p>
    </div>
    </div>
</section>