// Keep track of selected size & stock
let selectedSize = null;
let selectedStock = null;
let isLoading = false;

const quantityInput = document.getElementById("quantity-input");
const stockInfo = document.getElementById("stock-info");
const cartButton = document.querySelector(".cart-btn");

// --------------------------
// Size Selection Handling
// --------------------------
document.querySelectorAll(".size-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
        // Reset all buttons
        document
            .querySelectorAll(".size-btn")
            .forEach((b) =>
                b.classList.remove("border-gray-900", "bg-gray-100")
            );
        document
            .querySelectorAll(".size-btn")
            .forEach((b) =>
                b.classList.add("border-gray-300", "hover:border-gray-400")
            );

        // Set selected
        selectedSize = btn.dataset.size;
        selectedStock = parseInt(btn.dataset.stock);

        btn.classList.add("border-gray-900", "bg-gray-100");

        // Reset quantity to 1
        if (quantityInput) quantityInput.value = 1;

        // Show stock info
        if (stockInfo) {
            stockInfo.textContent = `Available: ${selectedStock}`;
            stockInfo.classList.remove("hidden");
        }
    });
});

// --------------------------
// Add to Cart Function
// --------------------------
async function addToCart(productId) {
    if (!selectedSize) {
        sendToast("error", "Please select a size");
        return;
    }

    if (isLoading) return;
    isLoading = true;

    try {
        const response = await fetch("/api/cart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Authorization:
                    "Bearer " +
                    document.querySelector("meta[name=api-token]").content,
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]")
                    .content,
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: parseInt(quantityInput.value || 1),
                size: selectedSize,
            }),
        });

        const data = await response.json();
        if (data.success) {
            sendToast("success", "Product added to cart successfully!");
            await updateCartCount();

            // ✅ Soft Refresh: reload without losing scroll or flash
            setTimeout(() => {
                if ("scrollRestoration" in history) {
                    history.scrollRestoration = "manual";
                }
                const scrollY = window.scrollY;
                location.reload();
                window.scrollTo(0, scrollY);
            }, 800); // wait for toast to show
        } else {
            sendToast("error", data.message || "Failed to add to cart");
        }
    } catch (error) {
        console.error("Cart error:", error);
        sendToast("error", "Something went wrong. Please try again.");
    } finally {
        isLoading = false;
    }
}

// --------------------------
// Cart Button Click
// --------------------------
if (cartButton) {
    cartButton.addEventListener("click", () => {
        const productId = cartButton.dataset.productId;
        addToCart(productId);
    });
}

// --------------------------
// Update Cart Count Badge
// --------------------------
async function updateCartCount() {
    try {
        const response = await fetch("/api/cart/count", {
            headers: {
                Authorization:
                    "Bearer " +
                    document.querySelector("meta[name=api-token]").content,
            },
        });
        const data = await response.json();
        const cartCountElement = document.getElementById("cart-count");
        if (cartCountElement) {
            cartCountElement.textContent = data;
            cartCountElement.style.display = data > 0 ? "flex" : "none";
        }
    } catch (error) {
        console.error("Could not update cart count", error);
    }
}

// --------------------------
// Toast Helper
// --------------------------
function sendToast(type, message) {
    window.dispatchEvent(
        new CustomEvent("toast", { detail: { type, message } })
    );
}
