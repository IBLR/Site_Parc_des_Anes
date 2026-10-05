// -------------------------------------------------
// Donation status update
// -------------------------------------------------

/**
 * Automatically submits the donation status form when the status changes.
 */
document
    .querySelectorAll(".donation-status-select")
    .forEach(select => {

        select.addEventListener("change", () => {
            select.form.submit();
        });

    });