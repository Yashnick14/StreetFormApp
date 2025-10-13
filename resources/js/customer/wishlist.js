import axios from "axios";

window.addToWishlist = async function (productId) {
    try {
        const response = await axios.post(
            "/api/wishlist",
            { product_id: productId },
            {
                headers: {
                    Authorization:
                        "Bearer " +
                        document.querySelector("meta[name=api-token]").content,
                    "X-CSRF-TOKEN": document.querySelector(
                        "meta[name=csrf-token]"
                    ).content,
                },
            }
        );

        if (response.data.success) {
            window.dispatchEvent(
                new CustomEvent("toast", {
                    detail: { type: "success", message: "Added to wishlist!" },
                })
            );
        } else {
            window.dispatchEvent(
                new CustomEvent("toast", {
                    detail: {
                        type: "error",
                        message: response.data.message || "Already in wishlist",
                    },
                })
            );
        }
    } catch (err) {
        console.error(err);
        let msg = err.response?.data?.message || "Failed to add to wishlist";
        window.dispatchEvent(
            new CustomEvent("toast", {
                detail: { type: "error", message: msg },
            })
        );
    }
};

window.removeFromWishlist = async function (itemId) {
    try {
        const response = await axios.delete(`/api/wishlist/${itemId}`, {
            headers: {
                Authorization:
                    "Bearer " +
                    document.querySelector("meta[name=api-token]").content,
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]")
                    .content,
            },
        });

        if (response.data.success) {
            window.dispatchEvent(
                new CustomEvent("toast", {
                    detail: {
                        type: "success",
                        message: "Removed from wishlist!",
                    },
                })
            );
            // refresh wishlist UI
            if (typeof window.getWishlist === "function") {
                window.getWishlist();
            }
        }
    } catch (err) {
        console.error(err);
        let msg =
            err.response?.data?.message || "Failed to remove from wishlist";
        window.dispatchEvent(
            new CustomEvent("toast", {
                detail: { type: "error", message: msg },
            })
        );
    }
};

window.getWishlist = async function () {
    try {
        const response = await axios.get("/api/wishlist", {
            headers: {
                Authorization:
                    "Bearer " +
                    document.querySelector("meta[name=api-token]").content,
                "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]")
                    .content,
            },
        });

        const items = response.data.items || [];
        const container = document.getElementById("wishlist-container");
        const emptyBox = document.getElementById("wishlist-empty");
        const template = document.getElementById("wishlist-card-template");

        container.innerHTML = "";

        if (items.length === 0) {
            emptyBox.classList.remove("hidden");
            return;
        } else {
            emptyBox.classList.add("hidden");
        }

        items.forEach((item) => {
            const product = item.product;
            if (!product) return;

            const clone = template.content.cloneNode(true);

            // Update product-card values
            const link = clone.querySelector("a");
            if (link) link.href = `/products/${product.id}/view`;

            const img = clone.querySelector("img");
            if (img) {
                img.src = product.image
                    ? `/storage/${product.image}`
                    : "/assets/images/default.jpg";
                img.alt = product.name;
            }

            const name = clone.querySelector("h3");
            if (name) name.textContent = product.name;

            const desc = clone.querySelector("p.text-sm");
            if (desc)
                desc.textContent = product.description?.substring(0, 50) || "";

            const price = clone.querySelector("p.mt-2");
            if (price)
                price.textContent = "$" + Number(product.price).toFixed(2);

            // Hook remove button
            clone.querySelector(".remove-btn").onclick = () =>
                removeFromWishlist(item.id);

            container.appendChild(clone);
        });
    } catch (err) {
        console.error("Error fetching wishlist:", err);
    }
};

// Attach event listeners for product page buttons
document.addEventListener("DOMContentLoaded", () => {
    // If on product view page
    document.querySelectorAll(".wishlist-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            const productId = btn.getAttribute("data-product-id");
            if (productId) {
                window.addToWishlist(productId);
            }
        });
    });

    // If on wishlist page, load wishlist items
    if (document.getElementById("wishlist-container")) {
        window.getWishlist();
    }
});

// ========== PRODUCT PAGE QUANTITY LIMIT ==========
document.addEventListener("DOMContentLoaded", () => {
    const sizeButtons = document.querySelectorAll(".size-btn");
    const quantityInput = document.getElementById("quantity-input");
    const stockInfo = document.getElementById("stock-info");

    if (!sizeButtons.length || !quantityInput) return; // not on product page

    sizeButtons.forEach((button) => {
        button.addEventListener("click", () => {
            sizeButtons.forEach((btn) =>
                btn.classList.remove(
                    "ring-2",
                    "ring-black",
                    "bg-gray-900",
                    "text-white"
                )
            );
            button.classList.add(
                "ring-2",
                "ring-black",
                "bg-gray-900",
                "text-white"
            );

            const stock = parseInt(button.dataset.stock, 10);
            quantityInput.max = stock;
            quantityInput.value = 1;

            stockInfo.textContent = `Only ${stock} left in stock`;
            stockInfo.classList.remove("hidden");
        });
    });

    quantityInput.addEventListener("input", () => {
        const max = parseInt(quantityInput.max, 10);
        const val = parseInt(quantityInput.value, 10);
        if (val > max) quantityInput.value = max;
    });
});
