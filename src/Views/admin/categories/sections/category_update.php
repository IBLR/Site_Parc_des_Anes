<?php if (!isset($item)): ?>

<form
    id="category-form-<?= $editItem['id'] ?>"
    method="post"
    action="?url=admin/categories/update"
    novalidate
>

    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars(
            $csrfCategoryUpdate,
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


<tr id="category-<?= $item['id'] ?>" class="admin-categories-list-table-edit-row">

    <td>

        <input
            form="category-form-<?= $item['id'] ?>"
            type="text"
            name="label"
            value="<?= htmlspecialchars($item['label']) ?>"
            required
        >

        <?php if (!empty($updateErrors['label'])): ?>

            <div class="error-message">
                <?= htmlspecialchars($updateErrors['label']) ?>
            </div>

        <?php endif; ?>

    </td>


    <td>

        <textarea
            form="category-form-<?= $item['id'] ?>"
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
            form="category-form-<?= $item['id'] ?>"
            type="number"
            name="display_order"
            min="1"
            value="<?= htmlspecialchars($item['display_order']) ?>"
            required
        >

        <?php if (!empty($updateErrors['display_order'])): ?>

            <div class="error-message">
                <?= htmlspecialchars($updateErrors['display_order']) ?>
            </div>

        <?php endif; ?>

    </td>


    <td class="admin-categories-list-table-actions">

        <button
            type="submit"
            form="category-form-<?= $item['id'] ?>"
            class="admin-btn"
        >
            Enregistrer
        </button>

        <a
            href="<?= BASE_URL ?>/?url=admin/categories#category-<?= $item['id'] ?>"
            class="admin-btn"
        >
            Annuler
        </a>

    </td>

</tr>