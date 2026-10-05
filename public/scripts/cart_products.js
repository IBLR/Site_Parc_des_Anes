// -------------------------------------------------
// Cart initialization and data management
// -------------------------------------------------

/**
 * Container used to display cart items.
 */
const cartItemsContainer = document.getElementById("cart-items");

/**
 * Elements displaying the cart total.
 */
const cartTotal = document.querySelectorAll(".cart-total");

/**
 * Current cart contents.
 */
let cart = [];


/**
 * Loads the cart and delivery data from localStorage.
 * After a successful order, the stored cart and delivery data
 * are removed and the cart is reset.
 */
function loadCart() {
    const orderSuccess =
        document.querySelector('[data-order-success="true"]');

    if (orderSuccess) {
        localStorage.removeItem("cart");
        localStorage.removeItem("cartDelivery");
        cart = [];

    } else {
        const storedCart = localStorage.getItem("cart");

        if (storedCart) {
            cart = JSON.parse(storedCart);
        } else {
            cart = [];
        }
    }

    updateCartDisplay();
}


/**
 * Saves the current cart in localStorage.
 */
function saveCart() {
    localStorage.setItem("cart", JSON.stringify(cart));
}


/**
 * Updates the cart item count displayed in the interface.
 */
function updateCartDisplay() {
    const cartCount = cart.reduce(
        (total, item) => total + item.quantity,
        0
    );

    const cartLink = document.querySelector(".cart-display");

    if (cartLink) {
        cartLink.textContent = `(${cartCount})`;
    }
}



// -------------------------------------------------
// Add products to the cart
// -------------------------------------------------

/**
 * Binds the "Add to Cart" buttons to the products displayed in the shop.
 */
function bindAddToCartButtons() {
    document
        .querySelectorAll(".shop-product-card button")
        .forEach(btn => {

            btn.addEventListener("click", () => {
                const card = btn.closest(".shop-product-card");

                const product = {
                    image:
                        card.querySelector("img")?.src ||
                        "assets/img/placeholders/img-not-available.png",
                    id: card.dataset.id,
                    reference: card.dataset.reference,
                    name: card.querySelector("h3")?.textContent || "",
                    price: parseFloat(card.dataset.price) || 0
                };

                addToCart(product);
            });
        });
}


/**
 * Adds a product to the cart or increases its quantity
 * when the product is already present.
 *
 * @param {Object} product - Product data to add to the cart.
 */
function addToCart(product) {
    const existing = cart.find(item => item.id === product.id);

    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({
            image: product.image,
            id: product.id,
            reference: product.reference,
            name: product.name,
            price: product.price,
            quantity: 1
        });
    }

    saveCart();
    renderCart();
    updateCartDisplay();
    showCartNotification(product.name);
}



// -------------------------------------------------
// Cart notifications
// -------------------------------------------------

/**
 * Displays a temporary notification after a product is added to the cart.
 *
 * @param {string} productName - Name of the added product.
 */
function showCartNotification(productName) {
    const notification =
        document.getElementById("cart-notification");

    if (!notification) {
        return;
    }

    notification.innerHTML = "";

    const text = document.createElement("span");
    text.textContent = "Produit ajouté au panier : ";

    const name = document.createElement("strong");
    name.textContent = productName;

    notification.append(text, name);
    notification.classList.add("is-visible");

    setTimeout(() => {
        notification.classList.remove("is-visible");
    }, 3000);
}



// -------------------------------------------------
// Cart quantity and item management
// -------------------------------------------------

/**
 * Updates the quantity of a cart item.
 *
 * Removes the item when its quantity reaches zero.
 *
 * @param {number} index - Index of the item in the cart.
 * @param {number} change - Quantity change to apply.
 */
function updateQuantity(index, change) {
    cart[index].quantity += change;

    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }

    saveCart();
    renderCart();
    updateCartDisplay();
}


/**
 * Removes an item from the cart.
 *
 * @param {number} index - Index of the item to remove.
 */
function removeItemFromCart(index) {
    cart.splice(index, 1);

    saveCart();
    renderCart();
    updateCartDisplay();
}



// -------------------------------------------------
// Cart total calculation
// -------------------------------------------------

/**
 * Calculates the total amount of all products in the cart.
 *
 * @returns {number} Total cart amount.
 */
function calculateTotal() {
    return cart.reduce((total, item) => {
        return total + item.price * item.quantity;
    }, 0);
}



// -------------------------------------------------
// Cart item creation and display
// -------------------------------------------------

/**
 * Creates the DOM element representing a cart item.
 *
 * @param {Object} item - Cart item data.
 * @param {number} index - Index of the item in the cart.
 * @returns {HTMLElement} Cart item element.
 */
function createCartItemElement(item, index) {

    // Create the main list item container.
    const li = document.createElement("li");

    const img = document.createElement("img");
    img.src =
        item.image
            ? item.image
            : "assets/img/placeholders/img-not-available.png";
    img.alt =
        item.image
            ? (item.name || "")
            : "Image non disponible";

    img.onerror = () => {
        img.src = "assets/img/placeholders/img-not-available.png";
        img.alt = "Image non disponible";
    };

    const name = document.createElement("span");
    name.textContent = `${item.name}`;

    const minusBtn = document.createElement("button");
    minusBtn.type = "button";
    minusBtn.textContent = "−";
    minusBtn.addEventListener(
        "click",
        () => updateQuantity(index, -1)
    );

    const qty = document.createElement("span");
    qty.textContent = item.quantity;

    const plusBtn = document.createElement("button");
    plusBtn.type = "button";
    plusBtn.textContent = "+";
    plusBtn.addEventListener(
        "click",
        () => updateQuantity(index, 1)
    );

    const price = document.createElement("span");
    price.textContent =
        `${(item.price * item.quantity).toFixed(2)} €`;

    const deleteBtn = document.createElement("button");
    deleteBtn.type = "button";

    const icon = document.createElement("img");
    icon.src = "assets/icons/ui/icon-trash.svg";
    icon.alt = "Supprimer l'article";

    deleteBtn.appendChild(icon);
    deleteBtn.addEventListener(
        "click",
        () => removeItemFromCart(index)
    );

    // Build the top section containing the product name and delete button.
    const blockTop = document.createElement("div");
    blockTop.append(name, deleteBtn);
    blockTop.classList.add("cart-item-top");
    name.classList.add("high-text");
    deleteBtn.classList.add("cart-delete-btn");

    const unitRefItem = document.createElement("div");
    unitRefItem.append(`(Réf. ${item.reference})`);

    const unitPriceItem = document.createElement("div");
    unitPriceItem.append(`${item.price} € l'unité`);

    const unitItem = document.createElement("div");
    unitItem.append(unitRefItem, "-", unitPriceItem);
    unitItem.classList.add("cart-block-unit-item");

    // Build the quantity controls.
    const blockQty = document.createElement("div");
    blockQty.append(minusBtn, qty, plusBtn);
    blockQty.classList.add("cart-block-qty");
    minusBtn.classList.add("cart-qty-btn");
    plusBtn.classList.add("cart-qty-btn");

    const blockQtyPrice = document.createElement("div");
    blockQtyPrice.append(blockQty, price);
    blockQtyPrice.classList.add("cart-block-qty-price");
    price.classList.add("high-text");

    // Build the bottom section containing unit information and quantity.
    const blockBottom = document.createElement("div");
    blockBottom.append(unitItem, blockQtyPrice);
    blockBottom.classList.add("cart-item-bottom");

    const blockRight = document.createElement("div");
    blockRight.append(blockTop, blockBottom);
    blockRight.classList.add("cart-right-block-item");

    // Assemble the complete cart item.
    const blockGlobal = document.createElement("div");
    blockGlobal.append(img, blockRight);
    blockGlobal.classList.add("cart-global-block-item");
    img.classList.add("cart-img-item");

    li.appendChild(blockGlobal);

    return li;
}



// -------------------------------------------------
// Cart rendering
// -------------------------------------------------

/**
 * Renders the current cart contents in the interface.
 */
function renderCart() {
    if (!cartItemsContainer || cartTotal.length === 0) {
        return;
    }

    cartItemsContainer.innerHTML = "";

    if (cart.length === 0) {
        cartItemsContainer.innerHTML =
            '<li class="cart-empty">Votre panier est vide</li>';

        cartTotal.forEach(element => {
            element.textContent = "";
        });

        return;
    }

    cart.forEach((item, index) => {
        const cartItemElement =
            createCartItemElement(item, index);

        cartItemsContainer.appendChild(cartItemElement);
    });

    const deliveryLine = document.createElement("li");
    deliveryLine.textContent = `Livraison : GRATUIT`;

    cartTotal.forEach(element => {
        element.textContent =
            `Total : ${calculateTotal().toFixed(2)} €`;

        element.classList.add("high-text");
    });

    const cartBlockTotal = document.createElement("div");
    cartBlockTotal.append(deliveryLine, cartTotal[0]);
    cartBlockTotal.classList.add("cart-block-total");

    cartItemsContainer.appendChild(cartBlockTotal);
}



// -------------------------------------------------
// Cart initialization
// -------------------------------------------------

/**
 * Initializes the cart by loading its data
 * and rendering its current state.
 */
function initCart() {
    loadCart();
    renderCart();
}

initCart();