// -------------------------------------------------
// Restore password change section position
// -------------------------------------------------

/**
 * Returns to the password change section after the page reloads
 * following form submission.
 */
document.addEventListener('DOMContentLoaded', () => {
    const success = document.querySelector(
        '#account-password-success'
    );

    if (success) {
        document
            .querySelector('#anchor-account-password')
            ?.scrollIntoView();

        return;
    }

    const hasErrors = [
        ...document.querySelectorAll('.error-message')
    ].some(error => error.textContent.trim() !== '');

    if (hasErrors) {
        document
            .querySelector('#anchor-account-password')
            ?.scrollIntoView();
    }
});