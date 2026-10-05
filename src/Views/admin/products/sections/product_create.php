<section>

    <h3 class="admin-h3">Créer un produit</h3>

    <?php
        $categoriesById = [];

        foreach ($categories as $category) {
            $categoriesById[$category['id']] = $category;
        }
    ?>

    <form
        id="create-product-form"
        method="post"
        enctype="multipart/form-data"
        action="?url=admin/produits/create"
        class="admin-product-create-section"
        novalidate
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $csrfProductCreate,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >


        <div class="admin-product-create-fields">

            <div class="admin-product-create-field admin-product-create-category">
                <label for="product-create-category">
                    Catégorie
                </label>

                <select
                    id="product-create-category"
                    name="category_id"
                    required
                >
                    <option value="">
                        -- Choisir une catégorie --
                    </option>

                    <?php foreach ($categories as $category): ?>

                        <option
                            value="<?= $category['id'] ?>"
                            <?= (
                                !empty($old['category'])
                                && $old['category'] == $category['id']
                            ) ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($category['label']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <?php if (!empty($createErrors['category'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($createErrors['category']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="admin-product-create-field admin-product-create-name">
                <label for="product-create-name">
                    Nom du produit
                </label>

                <input
                    id="product-create-name"
                    type="text"
                    name="name"
                    autocomplete="off"
                    value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                    required
                >

                <?php if (!empty($createErrors['name'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($createErrors['name']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="admin-product-create-field admin-product-create-price">
                <label for="product-create-price">
                    Prix
                </label>

                <input
                    id="product-create-price"
                    type="number"
                    step="0.01"
                    min="0"
                    name="price"
                    value="<?= htmlspecialchars($old['price'] ?? '') ?>"
                    required
                >

                <?php if (!empty($createErrors['price'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($createErrors['price']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="admin-product-create-field admin-product-create-weight">
                <label for="product-create-weight">
                    Poids
                </label>

                <input
                    id="product-create-weight"
                    type="text"
                    name="weight"
                    value="<?= htmlspecialchars($old['weight'] ?? '') ?>"
                >

                <?php if (!empty($createErrors['weight'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($createErrors['weight']) ?>
                    </div>
                <?php endif; ?>
            </div>


            <div class="admin-product-create-field admin-product-create-available">
                <label for="product-create-available">
                    Disponible
                </label>

                <input
                    id="product-create-available"
                    type="checkbox"
                    name="is_available"
                    value="1"
                    <?= !empty($old['is_available']) ? 'checked' : '' ?>
                >
            </div>

            <div class="admin-product-create-field admin-product-create-description">
                <label for="product-create-description">
                    Description
                </label>

                <textarea
                    id="product-create-description"
                    name="description"
                    required
                ><?= htmlspecialchars($old['description'] ?? '') ?></textarea>

                <?php if (!empty($createErrors['description'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($createErrors['description']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="admin-product-create-field admin-product-create-image">
                <label for="product-create-image">
                    Image
                </label>

                <input
                    id="product-create-image"
                    type="file"
                    name="image"
                    class="imageInput"
                    accept=".jpg,.jpeg,.png,.webp"
                >

                <?php if (!empty($createErrors['image'])): ?>
                    <div class="error-message">
                        <?= htmlspecialchars($createErrors['image']) ?>
                    </div>
                <?php endif; ?>

                <div class="admin-product-image-preview-container">

                    <div class="admin-product-image-preview-content">

                        <small class="previewText admin-product-image-preview-text">
                            Prévisualisation de l’image
                        </small>

                        <img
                            class="imagePreview admin-product-image-preview"
                            style="display:none;"
                            alt=""
                        >

                    </div>

                    <button
                        type="button"
                        class="removeImagePreview admin-btn"
                        style="display:none;"
                    >
                        Supprimer l'image
                    </button>

                </div>
            </div>
        </div>


        <div class="admin-product-create-actions">
            <button
                type="submit"
                class="admin-btn"
            >
                Créer
            </button>

            <button
                type="button"
                id="resetForm"
                class="admin-btn"
            >
                Annuler
            </button>

            <?php if (
                !empty($success)
                && ($successType ?? '') === 'product_create'
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