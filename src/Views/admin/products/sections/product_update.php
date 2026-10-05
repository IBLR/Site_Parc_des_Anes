<?php if (!isset($item)): ?>
    <form
        id="product-form-<?= $editItem['id'] ?>"
        method="post"
        enctype="multipart/form-data"
        action="?url=admin/produits/update"
        novalidate
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $csrfProductUpdate,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

        <input
            type="hidden"
            name="id"
            value="<?= $editItem['id'] ?>"
        >

    </form>
<?php return; endif; ?>


<tr
    id="product-<?= $item['id'] ?>"
    class="admin-products-list-table-edit-row"
>

    <td>
        <?= htmlspecialchars($item['reference']) ?>
    </td>


    <td>
        <select
            form="product-form-<?= $item['id'] ?>"
            name="category_id"
        >

            <?php foreach ($categories as $category): ?>

                <option
                    value="<?= $category['id'] ?>"
                    <?= $item['category_id'] == $category['id']
                        ? 'selected'
                        : '' ?>
                >
                    <?= htmlspecialchars($category['label']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <?php if (!empty($updateErrors['category'])): ?>

            <div class="error-message">
                <?= htmlspecialchars($updateErrors['category']) ?>
            </div>

        <?php endif; ?>
    </td>


    <td>
        <input
            form="product-form-<?= $item['id'] ?>"
            type="text"
            name="name"
            value="<?= htmlspecialchars($item['name']) ?>"
            required
        >

        <?php if (!empty($updateErrors['name'])): ?>

            <div class="error-message">
                <?= htmlspecialchars($updateErrors['name']) ?>
            </div>

        <?php endif; ?>
    </td>


    <td>
        <textarea
            form="product-form-<?= $item['id'] ?>"
            name="description"
            required
        ><?= htmlspecialchars($item['description']) ?></textarea>

        <?php if (!empty($updateErrors['description'])): ?>

            <div class="error-message">
                <?= htmlspecialchars($updateErrors['description']) ?>
            </div>

        <?php endif; ?>
    </td>


    <td>
        <input
            form="product-form-<?= $item['id'] ?>"
            type="number"
            name="price"
            step="0.01"
            min="0"
            value="<?= htmlspecialchars($item['price']) ?>"
            required
        >

        <?php if (!empty($updateErrors['price'])): ?>

            <div class="error-message">
                <?= htmlspecialchars($updateErrors['price']) ?>
            </div>

        <?php endif; ?>
    </td>


    <td>
        <input
            form="product-form-<?= $item['id'] ?>"
            type="text"
            name="weight"
            value="<?= htmlspecialchars(
                $item['weight'] ?? '',
                ENT_QUOTES
            ) ?>"
        >

        <?php if (!empty($updateErrors['weight'])): ?>

            <div class="error-message">
                <?= htmlspecialchars($updateErrors['weight']) ?>
            </div>

        <?php endif; ?>
    </td>


    <td>
        <input
            form="product-form-<?= $item['id'] ?>"
            type="checkbox"
            name="is_available"
            value="1"
            <?= $item['is_available'] ? 'checked' : '' ?>
        >
    </td>


    <td>
        <div class="admin-product-image-block">

            <?php if (!empty($item['image'])): ?>
                <img
                    class="imagePreview admin-product-edit-image"
                    src="<?= UPLOAD_PRODUCTS_URL . htmlspecialchars($item['image']) ?>"
                    alt="<?= htmlspecialchars($item['name']) ?>"
                >
            <?php else: ?>
                <img
                    class="imagePreview admin-product-edit-image"
                    style="display:none;"
                    alt=""
                >
            <?php endif; ?>

            <button
                type="button"
                class="removeImagePreview admin-btn"
                style="<?= !empty($item['image']) ? '' : 'display:none;' ?>"
            >
                Supprimer l'image
            </button>

        </div>

        <small class="previewText admin-product-edit-preview-text">
            Nouvelle image sélectionnée
        </small>

        <small class="noImageText admin-product-edit-no-image-text">
            Aucune image sélectionnée
        </small>

        <input
            form="product-form-<?= $item['id'] ?>"
            type="file"
            name="image"
            class="imageInput admin-product-size-input-img"
            accept=".jpg,.jpeg,.png,.webp"
        >

        <input
            type="hidden"
            form="product-form-<?= $item['id'] ?>"
            name="delete_image"
            class="deleteImageFlag"
            value="0"
        >
    </td>


    <td class="admin-products-list-table-actions">
        <button
            type="submit"
            form="product-form-<?= $item['id'] ?>"
            class="admin-btn"
        >
            Enregistrer
        </button>

        <a
            href="<?= BASE_URL ?>/?url=admin/produits#product-<?= $item['id'] ?>"
            class="admin-btn"
        >
            Annuler
        </a>
    </td>

</tr>