<section class="admin-category-list">

    <div class="admin-category-h3-message-block">

        <h3 class="admin-h3">Liste des catégories</h3>

        <?php if (!empty($success) && ($successType ?? '') === 'category_delete'): ?>
            <div class="success success-message">
                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($deleteError)): ?>
            <div class="error error-message">
                <?= htmlspecialchars($deleteError, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

    </div>


    <?php if ($editItem): ?>
        <?php require __DIR__ . '/category_update.php'; ?>
    <?php endif; ?>


    <?php if (empty($items)): ?>

        <p>Aucune catégorie enregistrée.</p>

    <?php else: ?>

        <div>

            <table class="admin-categories-list-table">

                <thead>
                    <tr>
                        <th>Catégorie</th>
                        <th>Description affichée dans la boutique</th>
                        <th>Ordre d'affichage</th>
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
                                require __DIR__ . '/category_update.php';
                            ?>

                        <?php else: ?>

                            <tr id="category-<?= $item['id'] ?>">

                                <td>
                                    <?= htmlspecialchars($item['label']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['description']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($item['display_order']) ?>
                                </td>

                                <td class="admin-categories-list-table-actions">

                                    <?php if (
                                        !empty($success)
                                        && ($successType ?? '') === 'category_update'
                                        && !empty($successCategoryId)
                                        && $successCategoryId == $item['id']
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
                                        href="<?= BASE_URL ?>/?url=admin/categories&edit=<?= $item['id'] ?>#category-<?= $item['id'] ?>"
                                        class="admin-btn"
                                    >
                                        Modifier
                                    </a>


                                    <form
                                        method="post"
                                        action="?url=admin/categories/delete"
                                        class="delete-category-form"
                                        data-label="<?= htmlspecialchars(
                                            $item['label'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                        novalidate
                                    >

                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= htmlspecialchars(
                                                $csrfCategoryDelete,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= $item['id'] ?>"
                                        >

                                        <button type="submit" class="admin-btn">
                                            Supprimer
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</section>