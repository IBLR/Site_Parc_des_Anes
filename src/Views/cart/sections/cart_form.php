<section>
    <h2 class="cart-h2">Informations de livraison</h2>

    <div class="cart-delivery-container">
        <div class="cart-delivery-field-block input-style">
            <div class="cart-delivery-field-subblock">
                <label for="last-name" class="high-text">Nom <span class="required-field" aria-hidden="true">*</span></label>
                <input type="text" id="last-name" name="last_name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>" required aria-describedby="error-last-name" placeholder="Ex : DUPONT">
            </div>
            <span class="error-message" id="error-last-name" role="alert" aria-live="assertive"><?= htmlspecialchars($errors['last_name'] ?? '') ?></span>
        </div>
        <div class="cart-delivery-field-block input-style">
            <div class="cart-delivery-field-subblock">
                <label for="first-name" class="high-text">Prénom <span class="required-field" aria-hidden="true">*</span></label>
                <input type="text" id="first-name" name="first_name" autocomplete="given-name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>" required aria-describedby="error-first-name" placeholder="Ex : Marie">
            </div>
            <span class="error-message" id="error-first-name" role="alert" aria-live="assertive"><?= htmlspecialchars($errors['first_name'] ?? '') ?></span>
        </div>
        <?php if (!empty($isAuthenticated)): ?>
            <div class="cart-delivery-field-block input-style">
                <div class="cart-delivery-field-subblock">
                    <label for="email" class="high-text">Email</label>
                    <input type="email" id="email" name="email" autocomplete="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" readonly aria-describedby="error-email" placeholder="Ex : marie.dupont@email.fr">
                </div>
                <span class="error-message" id="error-email" role="alert" aria-live="assertive"><?= htmlspecialchars($errors['email'] ?? '') ?></span>
            </div>
        <?php endif; ?>
        <div class="cart-delivery-field-block input-style">
            <div class="cart-delivery-field-subblock">
                <label for="phone" class="high-text">Téléphone </label>
                <input type="tel" id="phone" name="phone" autocomplete="tel" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" aria-describedby="error-phone" placeholder="Ex : 06 12 34 56 78">
            </div>
            <span class="error-message" id="error-phone" role="alert" aria-live="assertive"><?= htmlspecialchars($errors['phone'] ?? '') ?></span>
        </div>
        <div class="cart-delivery-field-block input-style">
            <div class="cart-delivery-field-subblock">
                <label for="address" class="high-text">Adresse <span class="required-field" aria-hidden="true">*</span></label>
                <input type="text" id="address" name="address" autocomplete="street-address" value="<?= htmlspecialchars($old['address'] ?? '') ?>" required aria-describedby="error-address" placeholder="Ex : 1 rue de Paris">
            </div>
            <span class="error-message" id="error-address" role="alert" aria-live="assertive"><?= htmlspecialchars($errors['address'] ?? '') ?></span>
        </div>
        <div class="cart-delivery-field-block input-style">
            <div class="cart-delivery-field-subblock">
                <label for="postcode" class="high-text">Code postal <span class="required-field" aria-hidden="true">*</span></label>
                <input type="text" id="postcode" name="postcode" value="<?= htmlspecialchars($old['postcode'] ?? '') ?>" required aria-describedby="error-postcode" placeholder="Ex : 75000">
            </div>
            <span class="error-message" id="error-postcode" role="alert" aria-live="assertive"><?= htmlspecialchars($errors['postcode'] ?? '') ?></span>
        </div>
        <div class="cart-delivery-field-block input-style">
            <div class="cart-delivery-field-subblock">
                <label for="city" class="high-text">Ville <span class="required-field" aria-hidden="true">*</span></label>
                <input type="text" id="city" name="city" value="<?= htmlspecialchars($old['city'] ?? '') ?>" required aria-describedby="error-city" placeholder="Ex : PARIS">
            </div>
            <span class="error-message" id="error-city" role="alert" aria-live="assertive"><?= htmlspecialchars($errors['city'] ?? '') ?></span>
        </div>
        </div>
    </div>

</section>
