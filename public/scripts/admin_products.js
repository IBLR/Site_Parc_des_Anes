// -------------------------------------------------
// Product creation form reset
// -------------------------------------------------

/**
 * Handles the cancel button in the product creation form.
 */
const resetBtn = document.getElementById("resetForm");

if (resetBtn) {
    resetBtn.addEventListener("click", function () {
        window.location.href =
            window.location.pathname +
            '?url=admin/produits';
    });
}



// -------------------------------------------------
// Product image preview and management
// -------------------------------------------------

/**
 * Handles product image previews, image selection,
 * and associated controls.
 */
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".imageInput").forEach(function (input) {

        const cell =
            input.closest("td") ||
            input.closest(".admin-product-create-image");

        const preview = cell.querySelector(".imagePreview");
        const removeBtn = cell.querySelector(".removeImagePreview");
        const previewText = cell.querySelector(".previewText");
        const noImageText = cell.querySelector(".noImageText");
        const deleteFlag = cell.querySelector(".deleteImageFlag");

        /**
         * Updates the image preview and control state.
         *
         * @param {string} state - Current image state: "none", "existing", or "new".
         * @param {string|null} src - Image source used for the preview.
         */
        function setState(state, src = null) {

            if (state === "none") {

                preview.removeAttribute("src");
                preview.style.display = "none";

                if (previewText) previewText.style.display = "none";

                if (noImageText) noImageText.style.display = "block";

                removeBtn.style.display = "none";

                input.value = "";

                if (deleteFlag) deleteFlag.value = "1";
            }

            if (state === "existing") {

                preview.src = src;
                preview.style.display = "block";

                if (previewText) previewText.style.display = "none";

                if (noImageText) noImageText.style.display = "none";

                removeBtn.style.display = "inline-block";

                if (deleteFlag) deleteFlag.value = "0";
            }

            if (state === "new") {

                preview.src = src;
                preview.style.display = "block";

                if (previewText) previewText.style.display = "inline";

                if (noImageText) noImageText.style.display = "none";

                removeBtn.style.display = "inline-block";

                if (deleteFlag) deleteFlag.value = "0";
            }
        }

        // Initialize the preview according to the current image state.
        if (preview && preview.getAttribute("src")) {
            setState("existing", preview.getAttribute("src"));
        } else {
            setState("none");
        }

        // Handle image selection and generate a local preview.
        input.addEventListener("change", function () {

            const filename =
                cell.querySelector(".admin-product-image-filename");

            if (!this.files.length) {
                if (filename) {
                    filename.textContent = "";
                }
                return;
            }

            if (filename) {
                filename.textContent = this.files[0].name;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                setState("new", e.target.result);
            };

            reader.readAsDataURL(this.files[0]);
        });

        // Remove the current image preview.
        removeBtn.addEventListener("click", function () {
            setState("none");
        });
    });
});



// -------------------------------------------------
// Scroll to targeted product
// -------------------------------------------------

/**
 * Visually centers the targeted element when editing a product.
 */
document.addEventListener("DOMContentLoaded", function () {

    if (!window.location.hash) {
        return;
    }

    const element = document.querySelector(window.location.hash);

    if (!element) {
        return;
    }

    // Wait for the browser to complete rendering before scrolling.
    requestAnimationFrame(() => {
        element.scrollIntoView({
            behavior: "auto",
            block: "center"
        });
    });
});



// -------------------------------------------------
// Automatic message hiding
// -------------------------------------------------

/**
 * Automatically hides success and error messages after a delay.
 */
document.addEventListener("DOMContentLoaded", function () {

    document
        .querySelectorAll(".error-message, .success-message")
        .forEach(function (message) {

            setTimeout(function () {

                message.style.transition = "opacity 0.5s ease";
                message.style.opacity = "0";

                setTimeout(function () {
                    message.remove();
                }, 500);

            }, 5000);
        });
});



// -------------------------------------------------
// Product deletion confirmation
// -------------------------------------------------

/**
 * Requests confirmation before deleting a product.
 */
document.addEventListener("DOMContentLoaded", () => {

    document
        .querySelectorAll(".delete-product-form")
        .forEach(form => {

            form.addEventListener("submit", function(e) {

                const name = this.dataset.name;
                const reference = this.dataset.reference;

                if (
                    !confirm(
                        `Voulez-vous supprimer le produit « ${name} » référencé « ${reference} » ?`
                    )
                ) {
                    e.preventDefault();
                }
            });
        });
});