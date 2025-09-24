// resources/js/admin/products.js

import axios from "axios";

const API_BASE = "/api/products";

window.showError = function (msg) {
    const el = document.getElementById("error-box");
    el.textContent = msg;
    el.classList.remove("hidden");
};
window.hideError = function () {
    document.getElementById("error-box").classList.add("hidden");
};

window.showModalLoading = function (text = "Loading...") {
    const loading = document.getElementById("modal-loading");
    const loadingText = document.getElementById("modal-loading-text");
    loadingText.textContent = text;
    loading.classList.remove("hidden");
    loading.classList.add("flex");
};

window.hideModalLoading = function () {
    const loading = document.getElementById("modal-loading");
    loading.classList.add("hidden");
    loading.classList.remove("flex");
};

window.showImageLoading = function (imageId) {
    const loading = document.getElementById(`${imageId}-loading`);
    const placeholder = document.getElementById(`placeholder-${imageId}`);
    if (loading && placeholder) {
        placeholder.classList.add("hidden");
        loading.classList.remove("hidden");
        loading.classList.add("flex");
    }
};

window.hideImageLoading = function (imageId) {
    const loading = document.getElementById(`${imageId}-loading`);
    if (loading) {
        loading.classList.add("hidden");
        loading.classList.remove("flex");
    }
};

window.setSaveButtonLoading = function (loading = false) {
    const btn = document.getElementById("save-btn");
    const spinner = document.getElementById("save-loading");
    const text = document.getElementById("save-text");

    if (loading) {
        btn.disabled = true;
        btn.classList.add("opacity-75", "cursor-not-allowed");
        spinner.classList.remove("hidden");
        text.textContent = "Saving...";
    } else {
        btn.disabled = false;
        btn.classList.remove("opacity-75", "cursor-not-allowed");
        spinner.classList.add("hidden");
        text.textContent = "Save";
    }
};

window.resetForm = function () {
    hideError();
    hideModalLoading();
    setSaveButtonLoading(false);
    document.getElementById("product-id").value = "";
    document.getElementById("product-form").reset();
    setPreview("", "preview-image", "placeholder-image");
    ["image2", "image3", "image4"].forEach((f) =>
        setPreview("", `preview-${f}`, `placeholder-${f}`)
    );
};

window.setPreview = function (url, previewId, placeholderId) {
    const img = document.getElementById(previewId);
    const ph = document.getElementById(placeholderId);
    const imageId = previewId.replace("preview-", "");

    hideImageLoading(imageId);

    if (url) {
        img.src = url;
        img.classList.remove("hidden");
        ph.classList.add("hidden");
    } else {
        img.src = "";
        img.classList.add("hidden");
        ph.classList.remove("hidden");
    }
};

window.previewImage = function (input, previewId, placeholderId) {
    const file = input.files && input.files[0];
    const imageId = previewId.replace("preview-", "");

    if (!file) return setPreview("", previewId, placeholderId);

    showImageLoading(imageId);

    const reader = new FileReader();
    reader.onload = (e) => {
        setTimeout(() => {
            setPreview(e.target.result, previewId, placeholderId);
        }, 500);
    };
    reader.readAsDataURL(file);
};

window.openModal = async function (id = null) {
    resetForm();
    document.getElementById("modal-title").textContent = id
        ? "Edit Product"
        : "Add New Product";
    document.getElementById("product-modal").classList.remove("hidden");
    document.getElementById("product-modal").classList.add("flex");

    if (!id) return;

    try {
        showModalLoading("Loading product data...");
        const res = await axios.get(`${API_BASE}/${id}`);
        const p = res.data?.data || res.data;

        document.getElementById("product-id").value = p.id;
        document.getElementById("name").value = p.name ?? "";
        document.getElementById("description").value = p.description ?? "";
        document.getElementById("price").value = p.price ?? "";
        document.getElementById("category_id").value =
            p.category?.id ?? p.category_id ?? "";
        document.getElementById("type").value = p.type ?? "";

        let sizes = {};
        try {
            if (typeof p.sizes === "string") {
                sizes = JSON.parse(p.sizes);
            } else {
                sizes = p.sizes || {};
            }
        } catch {
            sizes = {};
        }
        ["XS", "S", "M", "L", "XL"].forEach((s) => {
            document.getElementById(`size-${s}`).value = sizes[s] ?? 0;
        });

        if (p.image) {
            showImageLoading("image");
            setTimeout(
                () => setPreview(p.image, "preview-image", "placeholder-image"),
                300
            );
        }
        if (p.image2) {
            showImageLoading("image2");
            setTimeout(
                () =>
                    setPreview(
                        p.image2,
                        "preview-image2",
                        "placeholder-image2"
                    ),
                400
            );
        }
        if (p.image3) {
            showImageLoading("image3");
            setTimeout(
                () =>
                    setPreview(
                        p.image3,
                        "preview-image3",
                        "placeholder-image3"
                    ),
                500
            );
        }
        if (p.image4) {
            showImageLoading("image4");
            setTimeout(
                () =>
                    setPreview(
                        p.image4,
                        "preview-image4",
                        "placeholder-image4"
                    ),
                600
            );
        }

        hideModalLoading();
    } catch (e) {
        hideModalLoading();
        showError(
            "Failed to load product: " +
                (e.response?.data?.message || e.message)
        );
    }
};

window.closeModal = function () {
    document.getElementById("product-modal").classList.add("hidden");
    document.getElementById("product-modal").classList.remove("flex");
    resetForm();
};

document.addEventListener("DOMContentLoaded", () => {
    // Handle form submit
    document
        .getElementById("product-form")
        ?.addEventListener("submit", async (e) => {
            e.preventDefault();
            const id = document.getElementById("product-id").value || null;

            try {
                setSaveButtonLoading(true);
                hideError();

                const fd = new FormData();
                fd.append("name", document.getElementById("name").value);
                fd.append(
                    "description",
                    document.getElementById("description").value
                );
                fd.append("price", document.getElementById("price").value);
                fd.append(
                    "category_id",
                    document.getElementById("category_id").value
                );
                fd.append("type", document.getElementById("type").value);
                ["XS", "S", "M", "L", "XL"].forEach((s) =>
                    fd.append(
                        `sizes[${s}]`,
                        document.getElementById(`size-${s}`).value || 0
                    )
                );
                ["image", "image2", "image3", "image4"].forEach((fId) => {
                    const file = document.getElementById(fId).files[0];
                    if (file) fd.append(fId, file);
                });

                const url = id ? `${API_BASE}/${id}` : API_BASE;

                if (id) {
                    fd.append("_method", "PUT");
                }

                await axios.post(url, fd, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                closeModal();
                Livewire.dispatch("refresh");
            } catch (err) {
                setSaveButtonLoading(false);
                closeModal();
                const errors = err.response?.data?.errors;
                if (errors) {
                    const firstError = Object.values(errors)[0][0];
                    showError("❌ " + firstError);
                } else {
                    showError(
                        "Save failed: " +
                            (err.response?.data?.message || err.message)
                    );
                }
            }
        });

    // Checkbox handling
    const selectAll = document.getElementById("select-all");
    const editBtn = document.getElementById("edit-btn");
    const deleteBtn = document.getElementById("delete-btn");

    function getSelectedIds() {
        return Array.from(
            document.querySelectorAll(".product-checkbox:checked")
        ).map((cb) => cb.value);
    }

    function updateActionButtons() {
        const selected = getSelectedIds();
        editBtn.disabled = selected.length !== 1; // only one for edit
        deleteBtn.disabled = selected.length === 0; // one or many for delete
    }

    if (selectAll) {
        selectAll.addEventListener("change", () => {
            document.querySelectorAll(".product-checkbox").forEach((cb) => {
                cb.checked = selectAll.checked;
            });
            updateActionButtons();
        });
    }

    document.addEventListener("change", (e) => {
        if (e.target.classList.contains("product-checkbox")) {
            updateActionButtons();
        }
    });

    window.editSelectedProduct = function () {
        const ids = getSelectedIds();
        if (ids.length === 1) {
            openModal(ids[0]);
        }
    };

    window.openDeleteModalForSelected = function () {
        const ids = getSelectedIds();
        if (ids.length > 0) {
            // For now, just delete first (multi-delete can be added later)
            deleteProductId = ids[0];
            document.getElementById("delete-modal").classList.remove("hidden");
            document.getElementById("delete-modal").classList.add("flex");
        }
    };
});

let deleteProductId = null;

window.openDeleteModal = function (id) {
    deleteProductId = id;
    document.getElementById("delete-modal").classList.remove("hidden");
    document.getElementById("delete-modal").classList.add("flex");
};

window.closeDeleteModal = function () {
    document.getElementById("delete-modal").classList.add("hidden");
    document.getElementById("delete-modal").classList.remove("flex");
    deleteProductId = null;
};

window.confirmDeleteProduct = async function () {
    if (!deleteProductId) return;

    try {
        await axios.delete(`${API_BASE}/${deleteProductId}`);
        Livewire.dispatch("refresh");
        closeDeleteModal();
    } catch (err) {
        closeDeleteModal();
        showError(
            "Delete failed: " + (err.response?.data?.message || err.message)
        );
    }
};
