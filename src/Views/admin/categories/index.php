<?php $errors = $errors ?? []; $old = $old ?? []; ?>

<main class="content layout-section admin-section">

    <h2 class="admin-h2">Gestion des catégories de produits de la boutique</h2>

    <a href="<?= BASE_URL ?>/?url=admin" class="admin-come-back">
        ← Retour à l'accueil du tableau de bord
    </a>

    <?php require __DIR__ . '/sections/category_create.php'; ?>
    <?php require __DIR__ . '/sections/category_read.php'; ?>

</main>
