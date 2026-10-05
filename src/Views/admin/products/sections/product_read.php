<section class="admin-product-list">

    <?php
        $categoriesById = [];

        foreach ($categories as $category) {
            $categoriesById[$category['id']] = $category;
        }
    ?>


    <div class="admin-product-h3-message-block">

        <h3 class="admin-h3">Liste des produits</h3>

        <?php if (
            !empty($success)
            && ($successType ?? '') === 'product_delete'
        ): ?>

            <div class="success success-message">
                <?= htmlspecialchars(
                    $success,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </div>

        <?php endif; ?>

    </div>


    <?php if ($editItem): ?>
        <?php require __DIR__ . '/product_update.php'; ?>
    <?php endif; ?>


    <?php if (empty($items)): ?>
        <p>Aucun produit enregistré.</p>
    <?php else: ?>

        <table class="admin-products-list-table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Catégorie</th>
                    <th>Nom du produit</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Poids</th>
                    <th>Disponible</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($items as $item): ?>

                    <?php if (
                        !empty($editItem)
                        && $editItem['id'] == $item['id']
                    ): ?>

                        <?php
                            $item = $editItem;
                            require __DIR__ . '/product_update.php';
                        ?>

                    <?php else: ?>

                        <tr id="product-<?= $item['id'] ?>">

                            <td>
                                <?= htmlspecialchars($item['reference']) ?>
                            </td>


                            <td>
                                <?= htmlspecialchars(
                                    $categoriesById[$item['category_id']]['label'] ?? ''
                                ) ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($item['name']) ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($item['description']) ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($item['price']) ?> € / PCE
                            </td>


                            <td>
                                <?= htmlspecialchars($item['weight']) ?>
                            </td>


                            <td>
                                <?= $item['is_available'] ? 'Oui' : 'Non' ?>
                            </td>


                            <td>
                                <?php if (!empty($item['image'])): ?>
                                    <div class="admin-product-image-block">
                                        <img
                                            class="admin-product-edit-image"
                                            src="<?= UPLOAD_PRODUCTS_URL . htmlspecialchars($item['image']) ?>"
                                            alt="<?= htmlspecialchars($item['name']) ?>"
                                        >
                                    </div>
                                <?php else: ?>
                                    Aucune image
                                <?php endif; ?>

                            </td>

                            <td class="admin-products-list-table-actions">

                                <?php if (
                                    !empty($success)
                                    && ($successType ?? '') === 'product_update'
                                    && !empty($successProductId)
                                    && $successProductId == $item['id']
                                ): ?>

                                    <div class="success success-message">
                                        <?= htmlspecialchars(
                                            $success,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </div>
                                <?php endif; ?>

                                <a
                                    href="<?= BASE_URL ?>/?url=admin/produits&edit=<?= $item['id'] ?>#product-<?= $item['id'] ?>"
                                    class="admin-btn"
                                >
                                    Modifier
                                </a>

                                <form
                                    method="post"
                                    action="?url=admin/produits/delete"
                                    class="delete-product-form"
                                    data-name="<?= htmlspecialchars(
                                        $item['name'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    data-reference="<?= htmlspecialchars(
                                        $item['reference'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    novalidate
                                >

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars(
                                            $csrfProductDelete,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $item['id'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="admin-btn"
                                    >
                                        Supprimer
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endif; ?>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</section>