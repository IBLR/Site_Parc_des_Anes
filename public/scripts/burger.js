// -------------------------------------------------
// Burger menu opening and closing
// -------------------------------------------------

/**
 * Opening and closing of the burger menu.
 */
const navToggleBtn = document.querySelector('.header-nav-toggle-btn');
const nav = document.querySelector('#primary-navigation');

if (navToggleBtn && nav) {

    const firstNavLink = nav.querySelector('a');

    navToggleBtn.addEventListener('click', () => {

        const menuIsOpen = nav.classList.contains('is-open');

        if (menuIsOpen) {

            nav.classList.remove('is-open');
            navToggleBtn.setAttribute('aria-expanded', 'false');
            navToggleBtn.focus();

        } else {

            nav.classList.add('is-open');
            navToggleBtn.setAttribute('aria-expanded', 'true');

            if (firstNavLink) {
                firstNavLink.focus();
            }
        }
    });
}