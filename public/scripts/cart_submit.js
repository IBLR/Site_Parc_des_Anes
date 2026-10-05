// -------------------------------------------------
// Prepare cart data for form submission
// -------------------------------------------------

/**
 * Prepares the cart data before form submission.
 */
function prepareCartForSubmit() {

    /**
     * Hidden input used to transfer cart data to the server.
     */
    const cartDataInput = document.getElementById('cart-data');

    if (!cartDataInput) {
        return false;
    }

    cartDataInput.value = JSON.stringify(cart);

    return true;
}