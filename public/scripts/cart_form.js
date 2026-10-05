// -------------------------------------------------
// Delivery form initialization and data management
// -------------------------------------------------
/**
 * Handles the delivery form behavior.
 * Validation rules are defined in form_validator.js.
 */

/**
 * Delivery form element.
 */
const cartForm = document.querySelector('.cart-form-check');

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

if (cartForm) {

    /**
     * Retrieves the delivery information
     * and temporarily stores it in localStorage.
     */
    function saveDeliveryData() {

        const delivery = {};

        cartForm.querySelectorAll(
            'input[name="last_name"], input[name="first_name"], input[name="phone"], input[name="address"], input[name="postcode"], input[name="city"]'
        ).forEach(field => {

            delivery[field.name] = field.value;
        });

        localStorage.setItem(
            'cartDelivery',
            JSON.stringify(delivery)
        );
    }


    /**
     * Restores previously saved delivery information.
     */
    const savedDelivery = localStorage.getItem('cartDelivery');

    if (savedDelivery) {

        try {
            const delivery = JSON.parse(savedDelivery);

            Object.entries(delivery).forEach(([fieldName, value]) => {

                const field = cartForm.elements[fieldName];

                if (field) {
                    field.value = value;
                }
            });

        } catch (error) {
            localStorage.removeItem('cartDelivery');
        }
    }


    // -------------------------------------------------
    // Delivery form validation and submission
    // -------------------------------------------------
    /**
     * Removes a field error message
     * when the user starts editing the field again.
     */
    cartForm.querySelectorAll('input, textarea').forEach(field => {

        field.addEventListener('input', () => {

            const errorSpan = document.getElementById(
                `error-${field.id}`
            );

            if (errorSpan) {
                errorSpan.textContent = '';
            }

            saveDeliveryData();
        });
    });


    /**
     * Handles form submission and validation.
     */
    cartForm.addEventListener('submit', function (event) {

        event.preventDefault();

        // Clear previous validation errors.
        cartForm
            .querySelectorAll('.error-message')
            .forEach(span => {
                span.textContent = '';
            });


        // Run the shared form validation.
        const errors = validateForm(cartForm);


        // Display validation errors.
        Object.entries(errors).forEach(([fieldName, message]) => {

            const field = cartForm.elements[fieldName];

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


        // Stop processing when validation errors are present.
        if (Object.keys(errors).length !== 0) {
            return;
        }


        // Prepare the cart before submission.
        if (!prepareCartForSubmit()) {
            return;
        }


        // Retrieve interface elements used during processing.
        const submitButton = document.getElementById(
            'cart-submit-button'
        );

        const loginRequiredMessage = document.getElementById(
            'cart-login-required'
        );

        const processingMessage = document.getElementById(
            'cart-payment-processing'
        );

        
        // Admin accounts cannot submit an order.
        if (isUserAdmin) {

            if (loginRequiredMessage) {
                loginRequiredMessage.textContent =
                    'Commande impossible avec le statut Administrateur.';

                loginRequiredMessage.hidden = false;
            }

            return;
        }


        // Disable the button while the request is being processed.
        if (submitButton) {
            submitButton.disabled = true;
        }


        // Display the appropriate message based on authentication status.
        if (!isUserAuthenticated) {

            if (loginRequiredMessage) {
                loginRequiredMessage.textContent =
                    'Pour finaliser votre commande, veuillez vous connecter à votre compte.';

                loginRequiredMessage.hidden = false;
            }

        } else {

            if (processingMessage) {
                processingMessage.textContent =
                    'Traitement du paiement en cours…';

                processingMessage.hidden = false;
            }
        }


        // Delay submission to allow the message to be displayed.
        const delay = isUserAuthenticated ? 1500 : 3000;

        setTimeout(() => {
            cartForm.submit();
        }, delay);

    });
}