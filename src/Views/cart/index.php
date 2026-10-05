<?php $completed = $completed ?? false; ?>

<main
    class="content layout-section"
    data-user-authenticated="<?= !empty($isAuthenticated) ? 'true' : 'false' ?>"
    data-user-admin="<?= !empty($_SESSION['user']['isAdmin']) ? 'true' : 'false' ?>"
>

    <?php if (!empty($success)): ?>
        <p
            class="success-message cart-message"
            data-order-success="true"
        >
            <?= htmlspecialchars($success) ?>
        </p>
    <?php endif; ?>

    <?php if (!$completed): ?>
        <form
            action="<?= BASE_URL ?>/?url=panier/send"
            method="post"
            novalidate
            class="cart-form-check cart-form-order"
            id="cart-order-form"
        >

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($csrfToken) ?>"
            >

            <?php // Récupération du panier de LocalStorage ?>
            <input type="hidden" name="cart" id="cart-data">

            <?php
            $sections = [
                'cart_products',
                'cart_form',
                'cart_submit',
            ];

            foreach ($sections as $section) {
                $this->renderSection(__DIR__, $section, get_defined_vars());
            }

            ?>
        </form>

    <?php endif; ?>

</main>
