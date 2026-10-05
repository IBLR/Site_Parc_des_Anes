// -------------------------------------------------
// Donation amount selection and validation
// -------------------------------------------------

/**
 * Fixed donation amount cards.
 */
const cards = document.querySelectorAll('.fixed-amount-card');

/**
 * Input used to enter a custom donation amount.
 */
const otherAmountInput = document.getElementById('other-amount-input');

/**
 * Card used for the custom donation amount.
 */
const otherAmountCard = document.querySelector('.other-amount-card');

/**
 * Radio button associated with the custom donation amount.
 */
const otherAmountRadio = document.querySelector('input[name="donation_choice"][value="other"]');

/**
 * Hidden input containing the final donation amount submitted to the backend.
 */
const finalAmountInput = document.getElementById('donation-amount-final');

/**
 * Donation amount form.
 */
const form = document.querySelector('.donation-amount-form');

/**
 * Element displaying validation errors for the donation amount.
 */
const amountError = document.getElementById('donation-amount-error');

if (form) {
    /**
     * Deselect all fixed amount cards and reset their radio buttons.
     */
    function deselectAllCards() {
        cards.forEach(card => {
            const radio = card.closest('label').querySelector('input[name="donation_choice"][type="radio"]');

            radio.checked = false;
            card.classList.remove('selected');
            card.setAttribute('aria-pressed', 'false');
        });
    }

    /**
     * Reset the custom amount input, radio button, and card state.
     */
    function resetOtherAmount() {
        otherAmountInput.value = '';
        otherAmountRadio.checked = false;
        otherAmountCard.classList.remove('selected');
        otherAmountCard.setAttribute('aria-pressed', 'false');
    }

    /**
     * Select one fixed donation amount and update the submitted amount.
     */
    function selectFixedAmountCard(card) {
        const radio = card.closest('label').querySelector('input[name="donation_choice"][type="radio"]');

        amountError.hidden = true;

        deselectAllCards();
        resetOtherAmount();

        radio.checked = true;
        card.classList.add('selected');
        card.setAttribute('aria-pressed', 'true');

        otherAmountInput.required = false;

        finalAmountInput.value = radio.value;
    }

    /**
     * Configure mouse and keyboard interactions for fixed amount cards.
     */
    cards.forEach(card => {
        card.setAttribute('tabindex', '0');
        card.setAttribute('role', 'button');
        card.setAttribute('aria-pressed', 'false');

        // Select the card when it is clicked with the mouse.
        card.addEventListener('click', () => {
            selectFixedAmountCard(card);
        });

        // Select the card when Enter or Space is pressed.
        card.addEventListener('keydown', event => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault(); // Prevent the default Enter/Space behavior.
                selectFixedAmountCard(card);
            }
        });
    });

    /**
     * Manage the custom donation amount entered by the user.
     */
    otherAmountInput.addEventListener('input', () => {
        const hasValue = otherAmountInput.value.trim() !== '' && parseFloat(otherAmountInput.value) > 0;

        deselectAllCards();

        otherAmountRadio.checked = hasValue;

        if (hasValue) {
            amountError.hidden = true; // Hide the error when the amount is valid.
            otherAmountCard.classList.add('selected'); // Highlight the selected card.
            otherAmountCard.setAttribute('aria-pressed', 'true'); // Update the accessibility state.
            otherAmountInput.required = true; // Require a custom amount.
            finalAmountInput.value = otherAmountInput.value; // Store the amount for the backend.
        } else {
            otherAmountCard.classList.remove('selected');
            otherAmountCard.setAttribute('aria-pressed', 'false');
            otherAmountInput.required = false;
            finalAmountInput.value = '';
        }
    });

    /**
     * Validate the donation amount before submitting the form.
     */
    form.addEventListener('submit', event => {
        const value = Number(finalAmountInput.value.trim());

        if (!finalAmountInput.value.trim() || value <= 0) {
            event.preventDefault(); // Prevent form submission when the amount is invalid.
            amountError.hidden = false;
        } else {
            amountError.hidden = true;
        }
    });
}