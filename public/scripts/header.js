// -------------------------------------------------
// Header height and body spacing
// -------------------------------------------------

/**
 * Retrieves the header element based on the current page.
 */
const header =
    document.querySelector('.header') ||
    document.querySelector('.cart-header') ||
    document.querySelector('.donation-header') ||
    document.querySelector('.admin-header');


/**
 * Adjusts the body's top padding to match the header height.
 */
function updateBodyPadding() {
    document.body.style.paddingTop = header.offsetHeight + 'px';
}

window.addEventListener('resize', updateBodyPadding);
updateBodyPadding();



// -------------------------------------------------
// Scroll padding adjustment
// -------------------------------------------------

/**
 * Updates the HTML scroll-padding-top property to match the header height.
 */
function updateScrollPadding() {
    const headerHeight = header.offsetHeight;

    document.documentElement.style.scrollPaddingTop = headerHeight + 'px';
}

window.addEventListener('resize', updateScrollPadding);
updateScrollPadding();