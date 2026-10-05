// -------------------------------------------------
// Text normalization
// -------------------------------------------------

/**
 * Normalizes text by removing accents and converting it to lowercase.
 */
function normalizeText(text) {
    return text
        .normalize("NFD") // Decompose letters and accents into separate characters.
        .replace(/[\u0300-\u036f]/g, "") // Remove accent characters.
        .toLowerCase(); // Convert the text to lowercase.
}

