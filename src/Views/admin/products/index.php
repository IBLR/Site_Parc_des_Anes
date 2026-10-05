<?php $errors = $errors ?? []; $old = $old ?? []; ?>

<main class="content layout-section admin-section">

    <h2 class="admin-h2">Gestion des produits de la boutique</h2>

    <a href="<?= BASE_URL ?>/?url=admin" class="admin-come-back">
        ← Retour à l'accueil du tableau de bord
    </a>

    <?php require __DIR__ . '/sections/product_create.php'; ?>
    <?php require __DIR__ . '/sections/product_read.php'; ?>

</main>