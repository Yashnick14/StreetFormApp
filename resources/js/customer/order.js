import axios from "axios";

window.cancelOrder = async function (orderId) {
    if (!confirm("Are you sure you want to cancel this order?")) return;

    try {
        const response = await axios.patch(
            `/api/orders/${orderId}/cancel`,
            {},
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
                    detail: {
                        type: "success",
                        message: "Order cancelled successfully!",
                    },
                })
            );

            // Reload page to update status
            window.location.reload();
        } else {
            window.dispatchEvent(
                new CustomEvent("toast", {
                    detail: {
                        type: "error",
                        message: response.data.message || "Cancel failed",
                    },
                })
            );
        }
    } catch (err) {
        console.error(err);
        let msg =
            err.response?.data?.message || "Failed to cancel order. Try again.";
        window.dispatchEvent(
            new CustomEvent("toast", {
                detail: { type: "error", message: msg },
            })
        );
    }
};
