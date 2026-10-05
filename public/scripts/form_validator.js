// -------------------------------------------------
// Form validation helper functions
// -------------------------------------------------

/**
 * Contains the browser-side validation rules.
 *
 * JavaScript is used to improve the user experience.
 * PHP validation remains essential for security.
 */

/**
 * Validates a last name or first name.
 */
function validateName(value, fieldLabel) {
    value = value.trim();

    if (value === '') {
        return `${fieldLabel} est requis`;
    }

    if (value.length < 2) {
        return `${fieldLabel} est trop court`;
    }

    if (!/^[\p{L}\s'-]+$/u.test(value)) {
        return `${fieldLabel} contient des caractères invalides`;
    }

    return '';
}


/**
 * Validates an email address.
 */
function validateEmail(value) {
    value = value.trim();

    if (value === '') {
        return "L'email est requis";
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
        return 'L\'email est invalide';
    }

    return '';
}


/**
 * Normalizes a French phone number.
 *
 * Examples:
 * 06 12 34 56 78     -> 0612345678
 * 06.12.34.56.78     -> 0612345678
 * 06-12-34-56-78     -> 0612345678
 * 06/12/34/56/78     -> 0612345678
 * +33 6 12 34 56 78  -> 0612345678
 * +33612345678        -> 0612345678
 */
function normalizeFrenchPhone(value) {
    value = value.trim();

    // Remove spaces, dots, dashes, and slashes.
    value = value.replace(/[\s./-]+/g, '');

    // Convert +33 to 0.
    if (value.startsWith('+33')) {
        value = '0' + value.substring(3);
    }

    return value;
}


/**
 * Validates a French phone number.
 *
 * The phone number is optional.
 */
function validatePhone(value) {
    value = value.trim();

    if (value === '') {
        return '';
    }

    const normalizedPhone = normalizeFrenchPhone(value);

    if (!/^0[1-9]\d{8}$/.test(normalizedPhone)) {
        return 'Le numéro de téléphone est invalide (FR)';
    }

    return '';
}


/**
 * Validates a message.
 */
function validateMessage(value) {
    value = value.trim();

    if (value === '') {
        return 'Le message est requis';
    }

    if (value.length < 10) {
        return 'Le message doit contenir au moins 10 caractères';
    }

    return '';
}


/**
 * Validates an address.
 *
 * An address can contain numbers.
 * The original script's nameRegex is therefore not used,
 * as it would reject addresses such as "12 rue Victor Hugo".
 */
function validateAddress(value) {
    value = value.trim();

    if (value === '') {
        return "L'adresse est requise";
    }

    return '';
}


/**
 * Validates a French postcode.
 */
function validatePostcode(value) {
    value = value.trim();

    if (value === '') {
        return 'Le code postal est requis';
    }

    if (!/^(?!00000$)\d{5}$/.test(value)) {
        return 'Le code postal doit contenir 5 chiffres valides';
    }

    return '';
}


/**
 * Validates a city.
 */
function validateCity(value) {
    value = value.trim();

    if (value === '') {
        return 'La ville est requise';
    }

    if (!/^[\p{L}\s'-]+$/u.test(value)) {
        return 'La ville contient des caractères invalides';
    }

    return '';
}



// -------------------------------------------------
// Complete form validation
// -------------------------------------------------

/**
 * Validates all fields present in the form.
 *
 * The contact form only contains:
 * last_name, first_name, email, phone, message
 *
 * The delivery form contains:
 * last_name, first_name, email, phone,
 * address, postcode, city
 *
 * Missing fields are simply ignored.
 *
 * Returns an object containing the validation errors.
 */
function validateForm(form) {
    const errors = {};

    // Last name
    const lastName = form.elements['last_name'];

    if (lastName) {
        const error = validateName(lastName.value, 'Le nom');

        if (error) {
            errors.last_name = error;
        }
    }

    // First name
    const firstName = form.elements['first_name'];

    if (firstName) {
        const error = validateName(firstName.value, 'Le prénom');

        if (error) {
            errors.first_name = error;
        }
    }

    // Email
    const email = form.elements['email'];

    if (email) {
        const error = validateEmail(email.value);

        if (error) {
            errors.email = error;
        }
    }

    // Phone
    const phone = form.elements['phone'];

    if (phone) {
        const error = validatePhone(phone.value);

        if (error) {
            errors.phone = error;
        }
    }

    // Message
    const message = form.elements['message'];

    if (message) {
        const error = validateMessage(message.value);

        if (error) {
            errors.message = error;
        }
    }

    // Address
    const address = form.elements['address'];

    if (address) {
        const error = validateAddress(address.value);

        if (error) {
            errors.address = error;
        }
    }

    // Postcode
    const postcode = form.elements['postcode'];

    if (postcode) {
        const error = validatePostcode(postcode.value);

        if (error) {
            errors.postcode = error;
        }
    }

    // City
    const city = form.elements['city'];

    if (city) {
        const error = validateCity(city.value);

        if (error) {
            errors.city = error;
        }
    }

    return errors;
}