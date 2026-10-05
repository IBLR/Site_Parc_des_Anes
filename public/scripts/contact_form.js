// -------------------------------------------------
// Contact form validation and submission
// -------------------------------------------------

/**
 * Handles the behavior of the contact form.
 * Validation rules are defined in form_validator.js.
 */

const contactForm = document.querySelector('.contact-form-check');

if (contactForm) {

    /**
     * Removes a field error message
     * when the user starts editing the field again.
     */
    contactForm.querySelectorAll('input, textarea').forEach(field => {

        field.addEventListener('input', () => {

            const errorSpan = document.getElementById(
                `error-${field.id}`
            );

            if (errorSpan) {
                errorSpan.textContent = '';
            }
        });
    });


    /**
     * Handles form submission and validation.
     */
    contactForm.addEventListener('submit', function (event) {

        event.preventDefault();

        // Clear previous validation errors.
        contactForm
            .querySelectorAll('.error-message')
            .forEach(span => {
                span.textContent = '';
            });


        // Run the shared form validation.
        const errors = validateForm(contactForm);


        // Display validation errors.
        Object.entries(errors).forEach(([fieldName, message]) => {

            const field = contactForm.elements[fieldName];

            if (!field) {
                return;
            }

            const errorSpan = document.getElementById(
                `error-${field.id}`
            );

            if (errorSpan) {
                errorSpan.textContent = message;
            }
        });


        // Allow the browser to submit the form when there are no errors.
        if (Object.keys(errors).length === 0) {
            contactForm.submit();
        }
    });
}



// -------------------------------------------------
// Restore contact form position after submission
// -------------------------------------------------
/**
 * Returns to the contact form after the page reloads
 * following a submission with an error or success message.
 */
document.addEventListener('DOMContentLoaded', () => {

    const success =
        document.querySelector('#anchor-contact-success');

    if (success) {
        success.scrollIntoView();
        return;
    }

    const hasErrors =
        [...document.querySelectorAll('.error-message')]
            .some(error => error.textContent.trim() !== '');

    if (hasErrors) {
        document
            .querySelector('#anchor-contact-form')
            ?.scrollIntoView();
    }
});