<section>
    <div class="form-row-subcontainer">
        <?php if (!empty($errors['csrf'])): ?>
            <p class="error-message cart-message">
                <?= htmlspecialchars($errors['csrf']) ?>
            </p>
        <?php endif; ?>
        
        <?php if (!empty($errors['database'])): ?>
            <p class="error-message cart-message">
                <?= htmlspecialchars($errors['database']) ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($errors['cart'])): ?>
            <p class="error-message cart-message">
                <?= htmlspecialchars($errors['cart']) ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($errors['unavailable_products'])): ?>
            <p class="error-message cart-message">
                <?= htmlspecialchars($errors['unavailable_products']) ?><br>
                Merci de le(s) retirer de votre panier avant de valider votre commande.
            </p>
        <?php endif; ?>

        <p
            class="error-message cart-message"
            id="cart-login-required"
            hidden
        ></p>

        <p
            class="cart-payment-processing"
            id="cart-payment-processing"
            hidden
        ></p>

    </div>

    <div class="cart-delivery-validation">
        <p class="cart-total"></p>
        <button
            type="submit"
            id="cart-submit-button"
            class="btn btn--primary btn--square-lg"
        >
            Commander et payer
        </button>
    </div>
</section>
