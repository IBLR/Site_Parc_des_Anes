// -------------------------------------------------
// Message status update
// -------------------------------------------------

/**
 * Automatically submits the contact status form when the status changes.
 */
document
    .querySelectorAll(".contact-status-select")
    .forEach(select => {

        select.addEventListener("change", () => {
            select.form.submit();
        });

    });