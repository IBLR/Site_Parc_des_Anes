// -------------------------------------------------
// Donation form validation and submission
// -------------------------------------------------

/**
 * Checkout form used to submit the donation.
 */
const donationForm = document.querySelector('.donation-form-check');

/**
 * Indicates whether the current user is authenticated.
 */
const isUserAuthenticated =
    document.querySelector('[data-user-authenticated]')
        ?.dataset.userAuthenticated === 'true';

/**
 * Indicates whether the current user is admin.
 */
const isUserAdmin =
    document.querySelector('[data-user-admin]')
        ?.dataset.userAdmin === 'true';

if (donationForm) {

    donationForm.addEventListener('submit', function (event) {

        event.preventDefault();

        // Clear previous validation errors.
        donationForm
            .querySelectorAll('.error-message')
            .forEach(span => {
                span.textContent = '';
            });

        // Run client-side validation.
        const errors = validateForm(donationForm);

        // Display validation errors.
        Object.entries(errors).forEach(([fieldName, message]) => {

            const field = donationForm.elements[fieldName];

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

        // Stop submission when validation errors are present.
        if (Object.keys(errors).length !== 0) {
            return;
        }

        // Retrieve interface elements.
        const submitButton = donationForm.querySelector(
            'button[type="submit"]'
        );

        const loginRequiredMessage = document.getElementById(
            'donation-login-required'
        );

        const processingMessage = document.getElementById(
            'donation-payment-processing'
        );

        // Admin accounts cannot submit a donation.
        if (isUserAdmin) {

            if (loginRequiredMessage) {
                loginRequiredMessage.textContent =
                    'Don impossible avec le statut Administrateur.';

                loginRequiredMessage.hidden = false;
            }

            return;
        }

        // Disable the button while processing.
        if (submitButton) {
            submitButton.disabled = true;
        }

        // Save contact information when necessary.
        if (!isUserAuthenticated) {

            const contactData = {
                last_name:
                    document.getElementById('last-name')?.value || '',

                first_name:
                    document.getElementById('first-name')?.value || '',

                phone:
                    document.getElementById('phone')?.value || '',

                comment:
                    document.getElementById('comment')?.value || ''
            };

            sessionStorage.setItem(
                'donation_contact',
                JSON.stringify(contactData)
            );

            // Display the login message.
            if (loginRequiredMessage) {

                loginRequiredMessage.textContent =
                    'Pour finaliser votre don, veuillez vous connecter à votre compte.';

                loginRequiredMessage.hidden = false;
            }

        } else {

            // Display the processing message.
            if (processingMessage) {

                processingMessage.textContent =
                    'Traitement du paiement en cours…';

                processingMessage.hidden = false;
            }
        }

        // Delay form submission to display the message.

        const delay = isUserAuthenticated ? 2000 : 3000;

        setTimeout(() => {
            donationForm.submit();
        }, delay);

    });
}



// -------------------------------------------------
// Save contact information before changing the amount
// -------------------------------------------------

/**
 * Link used to return to the donation amount selection.
 */
const changeAmountLink = document.querySelector(
    '.donation-change-amount a'
);

if (changeAmountLink) {

    changeAmountLink.addEventListener('click', () => {

        const contactData = {
            last_name:
                document.getElementById('last-name')?.value || '',

            first_name:
                document.getElementById('first-name')?.value || '',

            phone:
                document.getElementById('phone')?.value || '',

            comment:
                document.getElementById('comment')?.value || ''
        };

        sessionStorage.setItem(
            'donation_contact',
            JSON.stringify(contactData)
        );
    });
}



// -------------------------------------------------
// Restore saved contact information
// -------------------------------------------------

/**
 * Restores temporarily saved donation contact information.
 */
const restoreDonationContact = () => {

    const savedContact =
        sessionStorage.getItem('donation_contact');

    if (!savedContact) {
        return;
    }

    // The checkout form is not available on this page.
    if (!donationForm) {
        return;
    }

    try {

        const contactData = JSON.parse(savedContact);

        document.getElementById('last-name').value =
            contactData.last_name || '';

        document.getElementById('first-name').value =
            contactData.first_name || '';

        document.getElementById('phone').value =
            contactData.phone || '';

        document.getElementById('comment').value =
            contactData.comment || '';

    } catch (error) {

        sessionStorage.removeItem('donation_contact');

        return;
    }

    // The contact information has been restored.
    // The temporary storage can now be removed.
    sessionStorage.removeItem('donation_contact');
};


if (document.readyState === 'loading') {

    document.addEventListener(
        'DOMContentLoaded',
        restoreDonationContact
    );

} else {

    restoreDonationContact();
}