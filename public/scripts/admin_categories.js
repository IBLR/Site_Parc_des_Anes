// -------------------------------------------------
// Category administration interactions
// -------------------------------------------------

/**
 * Handles category page interactions after the DOM is fully loaded.
 */
document.addEventListener("DOMContentLoaded", () => {

    // For category_create.php (Category creation cancelled)
    const resetCategoryBtn =
        document.getElementById("resetCategoryForm");

    if (resetCategoryBtn) {

        resetCategoryBtn.addEventListener(
            "click",
            function () {

                // Redirect to the category administration page.
                window.location.href =
                    window.location.pathname +
                    '?url=admin/categories';
            }
        );
    }

    // For category_read.php (Existing category deleted)
    document.querySelectorAll(".delete-category-form").forEach(form => {

        form.addEventListener("submit", function(e) {

            const label = this.dataset.label;

            // Ask for confirmation before submitting the deletion form.
            if (!confirm(`Voulez-vous supprimer la catégorie « ${label} » ?`)) {
                e.preventDefault();
            }
        });
    });
});