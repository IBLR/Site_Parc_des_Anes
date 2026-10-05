<section id="anchor-shop" class="shop-display-zone layout-section shop-specific-layout-section text-center">

    <?php // ================================================== ?>
    <?php // Cart access ?>
    <?php // ================================================== ?>

    <a href="<?= BASE_URL ?>/?url=panier" class="shop-cart-display">
        <div class="btn btn--primary btn--square-lg shop-cart-display-button">
            <img src="assets/icons/ui/icon-cart.png" alt="panier">
            <p class="high-text">Voir mon panier 
                <span class="cart-display">(0)</span>
            </p>
        </div>
    </a>



    <?php // ================================================== ?>
    <?php // Filters & Sort & Counter ?>
    <?php // ================================================== ?>

    <div class="shop-controls-container">
        <input type="text" id="searchInput" placeholder="Rechercher un produit">

        <select id="categorySelect">
            <option value="all">Toutes catégories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= (int)$category['id'] ?>">
                    <?= htmlspecialchars($category['label'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select id="priceSort">
            <option value="default">Trier par prix</option>
            <option value="asc">Prix croissant</option>
            <option value="desc">Prix décroissant</option>
        </select>
        <p id="product-counter"></p>
    </div>


    <?php // ================================================== ?>
    <?php // Shop (script shop.js) ?>
    <?php // ================================================== ?>

    <?php // Global view sort per price (without distinction of categories) ?>
    <div id="global-shop-header" class="shop-block-header">
        <h2>Votre sélection par prix</h2>
        <p>
            Découvrez l’ensemble de nos produits en ligne, classés par prix pour
            vous aider à faire votre choix.
        </p>
    </div>

    <div id="global-products" class="shop-product-cards-container"></div>
    <p id="shop-user-message"></p>


    <?php // All views selected, per categories ?>
    <?php foreach ($categories as $category): ?>

        <div class="shop-block-header">

            <h2
                class="category-title"
                data-category="<?= (int)$category['id'] ?>"
            >
                <?= htmlspecialchars($category['label'], ENT_QUOTES, 'UTF-8') ?>
            </h2>

            <div class="category-desc">
                <?= nl2br(
                    htmlspecialchars(
                        $category['description'],
                        ENT_QUOTES,
                        'UTF-8'
                    )
                ) ?>
            </div>

            <div
                id="shop-category-<?= (int)$category['id'] ?>"
                class="shop-product-cards-container"
                data-category="<?= (int)$category['id'] ?>"
            ></div>

        </div>

    <?php endforeach; ?>


    <div
        id="cart-notification"
        class="cart-notification-display"
        aria-live="polite"
    ></div>

</section>
