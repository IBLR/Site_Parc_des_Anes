<section>

    <h3 class="admin-h3">Créer une catégorie</h3>

    <form
        id="create-category-form"
        method="post"
        action="?url=admin/categories/create"
        novalidate
        class="admin-category-create-section"
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($csrfCategoryCreate, ENT_QUOTES, 'UTF-8') ?>"
        >

        <div class="admin-category-create-fields">

            <div class="admin-category-create-row-fields">

                <div class="admin-category-create-field">
                    <label for="category-create-label">
                        Nom de la catégorie
                    </label>

                    <input
                        id="category-create-label"
                        type="text"
                        name="label"
                        value="<?= htmlspecialchars($old['label'] ?? '') ?>"
                        required
                    >

                    <?php if (!empty($createErrors['label'])): ?>
                        <div class="error-message">
                            <?= htmlspecialchars($createErrors['label']) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="admin-category-create-field admin-category-order-field">
                    <label for="category-create-order">
                        Ordre d'affichage
                    </label>

                    <input
                        id="category-create-order"
                        type="number"
                        name="display_order"
                        min="1"
                        value="<?= htmlspecialchars($old['display_order'] ?? '') ?>"
                        required
                    >

                    <?php if (!empty($createErrors['display_order'])): ?>
                        <div class="error-message">
                            <?= htmlspecialchars($createErrors['display_order']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>


            <div class="admin-category-create-field">
                <label for="category-create-description">
                    Description
                </label>

                <textarea
                    id="category-create-description"
                    name="description"
                    required
                ><?= htmlspecialchars($old['description'] ?? '') ?></textarea>

                <?php if (!empty($createErrors['description'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($createErrors['description']) ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>


        <div class="admin-category-create-row-actions">

            <button type="submit" class="admin-btn">
                Créer
            </button>

            <button
                type="button"
                id="resetCategoryForm"
                class="admin-btn"
            >
                Annuler
            </button>

            <?php if (
                !empty($success) &&
                ($successType ?? '') === 'category_create'
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

    </form>

</section>