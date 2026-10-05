// -------------------------------------------------
// Order status update
// -------------------------------------------------

/**
 * Automatically submits the order status form when the status changes.
 */
document
    .querySelectorAll(".order-status-select")
    .forEach(select => {

        select.addEventListener("change", () => {
            select.form.submit();
        });

    });