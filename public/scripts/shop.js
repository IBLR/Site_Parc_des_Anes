// -------------------------------------------------
// Product card creation and data initialization
// -------------------------------------------------

const searchInput = document.getElementById("searchInput");
const categorySelect = document.getElementById("categorySelect");
const priceSort = document.getElementById("priceSort");

/**
 * Global container used to display products when sorting is active.
 */
const globalContainer = document.getElementById("global-products");

/**
 * Header displayed above the global sorted product view.
 */
const globalShopHeader = document.getElementById("global-shop-header");

const message = document.getElementById("shop-user-message");
const counter = document.getElementById("product-counter");


/**
 * Creates a product card from product data.
 */
function createProductCard(p) {
    /* Create the main product card. */
    const card = document.createElement("article");

    const img = document.createElement("img");
    img.src = p.image ? p.image : "assets/img/placeholders/img-not-available.png";
    img.alt = p.image ? (p.name || "") : "Image non disponible";

    img.onerror = () => {
        img.src = "assets/img/placeholders/img-not-available.png";
        img.alt = "Image non disponible";
    };

    const title = document.createElement("h3");
    title.textContent = p.name || "";

    const desc = document.createElement("p");
    desc.textContent = p.description || "";

    const price = document.createElement("p");
    const priceNum = parseFloat(p.price) || 0;

    price.textContent =
        priceNum.toLocaleString("fr-FR", {
            style: "currency",
            currency: "EUR",
            minimumFractionDigits: Number.isInteger(priceNum) ? 0 : 2,
            maximumFractionDigits: 2
        });

    if (p.weight) {
        price.textContent += ` / pce (${p.weight})`;
    } else {
        price.textContent += ` / pce`;
    }

    const btn = document.createElement("button");
    btn.textContent = "Ajouter";

    /* Store product data used by filters, sorting, and the cart. */
    card.dataset.name = normalizeText(p.name) || "";
    card.dataset.category = p.category_id;
    card.dataset.price = parseFloat(p.price) || 0;
    card.dataset.id = p.id;

    /* Store the commercial reference used by the cart. */
    card.dataset.reference = p.reference;

    /* Apply the main product card layout. */
    card.classList.add("shop-product-card");

    const topCard = document.createElement("div");
    topCard.append(img, title, desc);
    topCard.classList.add("shop-top-product-card");

    img.classList.add("shop-img-product-card");
    title.classList.add("shop-title-product-card", "high-text");
    desc.classList.add("shop-desc-product-card");

    const bottomCard = document.createElement("div");
    bottomCard.append(price, btn);
    bottomCard.classList.add("shop-bottom-product-card");

    price.classList.add("shop-price-product-card");
    btn.classList.add("btn", "btn--primary", "btn--square-sm");

    card.append(topCard, bottomCard);

    return card;
}


/**
 * Stores all loaded product cards.
 */
let allProducts = [];



// -------------------------------------------------
// Load products from the API
// -------------------------------------------------

/**
 * Loads products from the products API.
 */
async function loadProducts() {
    const shopZone = document.querySelector(".shop-display-zone");

    if (!shopZone) return;

    allProducts = [];

    message.textContent = "Chargement des articles...";
    hideAllSections();

    if (globalShopHeader) {
        globalShopHeader.style.display = "none";
    }

    try {
        const res = await fetch("?url=api/products");

        if (!res.ok) {
            throw new Error(`Erreur HTTP : ${res.status}`);
        }

        const data = await res.json();

        if (!Array.isArray(data)) {
            throw new Error("Les données reçues ne sont pas un tableau");
        }

        if (data.length === 0) {
            message.textContent = "Aucun article disponible pour le moment.";
            hideAllSections();
            return;
        }

        /* Create and append cards to their existing category containers. */
        data.forEach(p => {
            const card = createProductCard(p);

            allProducts.push(card);

            const container = document.getElementById(`shop-category-${p.category_id}`);

            if (container) {
                container.appendChild(card);
            }
        });

        /* Wait for cart.js before binding the "Add" buttons. */
        if (typeof bindAddToCartButtons === "function") {
            bindAddToCartButtons();
        }

        message.textContent = "";
        message.style.display = "none";

    } catch (e) {
        if (DEV_MODE) {
            console.error(e);
        }

        message.textContent = "Chargement des articles impossible";
        hideAllSections();

        if (counter) {
            counter.style.display = "none";
        }
    }
}



// -------------------------------------------------
// Manage product sections and global view
// -------------------------------------------------

/**
 * Displays the global product header when price sorting is active
 * and at least one product is visible.
 */
function updateGlobalHeader(visibleCount = allProducts.length) {
    if (!globalShopHeader) return;

    const sortActive = priceSort.value !== "default";

    globalShopHeader.style.display = (sortActive && visibleCount > 0) ? "" : "none";
}


/**
 * Hides all product category sections and the global container.
 */
function hideAllSections() {
    document.querySelectorAll(".category-title").forEach(title => {
        title.parentElement.style.display = "none";
    });

    if (globalContainer) {
        globalContainer.style.display = "none";
    }
}



// -------------------------------------------------
// Filter and sort products
// -------------------------------------------------

/**
 * Filters products according to the search text and selected category.
 */
function filterProducts(products) {
    const categorySelected = categorySelect.value;
    const searchText = normalizeText(searchInput.value);

    return products.filter(p => {
        if (searchText && !p.dataset.name.includes(searchText)) {
            return false;
        }

        if (categorySelected !== "all" && p.dataset.category !== categorySelected) {
            return false;
        }

        return true;
    });
}


/**
 * Sorts products according to their price.
 */
function sortProducts(products) {
    const sortOrder = priceSort.value;

    if (sortOrder === "default") {
        return products;
    }

    const productsCopy = Array.from(products);

    productsCopy.sort((a, b) => {
        const priceA = parseFloat(a.dataset.price);
        const priceB = parseFloat(b.dataset.price);

        if (sortOrder === "asc") {
            return priceA - priceB; // Increasing price.
        } else {
            return priceB - priceA; // Decreasing price.
        }
    });

    return productsCopy;
}



// -------------------------------------------------
// Render and display products
// -------------------------------------------------

/**
 * Renders the filtered and sorted products in the DOM.
 */
function renderProducts(products) {
    if (allProducts.length === 0) {
        if (globalContainer) {
            globalContainer.style.display = "none";
        }

        updateGlobalHeader(products.length);
        return;
    }

    document.querySelectorAll(".shop-product-card").forEach(p => {
        p.style.display = "none";
    });

    /* Display products in the global view when price sorting is active. */
    if (priceSort.value !== "default") {
        globalContainer.innerHTML = "";
        globalContainer.style.display = "";

        products.forEach(p => {
            p.style.display = "";
            globalContainer.appendChild(p);
        });

        /* Hide category sections when using the global view. */
        document.querySelectorAll(".category-title").forEach(
            t => (t.parentElement.style.display = "none")
        );

        updateGlobalHeader(products.length);
        return;
    }

    /* Display products inside their selected category sections. */
    globalContainer.style.display = "none";

    products.forEach(product => {
        const categoryId = product.dataset.category;
        const container = document.getElementById(`shop-category-${categoryId}`);

        if (container) {
            product.style.display = "";
            container.appendChild(product);
        }
    });

    displaySections();
    updateGlobalHeader(products.length);
}


/**
 * Displays or hides category sections according to their visible products.
 */
function displaySections() {
    if (allProducts.length === 0) return;

    document.querySelectorAll(".category-title").forEach(title => {
        const divCategory = title.parentElement;
        const titleCateg = title.dataset.category;
        const container = document.getElementById(`shop-category-${titleCateg}`);

        // const container = document.getElementById(titleCateg);

        const visible = container.querySelectorAll(
            ".shop-product-card:not([style*='display: none'])"
        ).length;

        divCategory.style.display = visible > 0 ? "" : "none";
    });
}



// -------------------------------------------------
// Update product counter and shop state
// -------------------------------------------------

/**
 * Updates the number of products displayed and the corresponding message.
 */
function updateCounter(productCount) {
    if (!counter || !message) return;

    const hasProducts = productCount > 0;

    counter.style.display = hasProducts ? "" : "none";
    message.style.display = hasProducts ? "none" : "";

    counter.textContent = hasProducts ? `Produits affichés : ${productCount}` : "";
    message.textContent = hasProducts ? "" : "Aucun produit ne correspond à votre recherche.";
}


/**
 * Applies the current filters and sorting, then updates the product display.
 */
function updateShop() {
    let visibleProducts = Array.from(allProducts);

    visibleProducts = filterProducts(visibleProducts);
    visibleProducts = sortProducts(visibleProducts);

    renderProducts(visibleProducts);
    updateCounter(visibleProducts.length);
    updateGlobalHeader(visibleProducts.length);
}



// -------------------------------------------------
// Initialize shop events and display
// -------------------------------------------------

/**
 * Initializes event listeners for the shop filters and sorting controls.
 */
function initEvents() {
    if (!searchInput || !categorySelect || !priceSort) {
        return;
    }

    searchInput.addEventListener("input", updateShop);
    categorySelect.addEventListener("change", updateShop);
    priceSort.addEventListener("change", updateShop);
}


/**
 * Initializes the shop by loading products and setting up its events.
 */
async function initShop() {
    await loadProducts();

    if (allProducts.length > 0) {
        updateShop();
    }

    initEvents();
}

initShop();