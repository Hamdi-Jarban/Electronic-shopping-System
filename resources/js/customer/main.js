document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (event) {
        const button = event.target.closest(".btn-add-cart");

        if (!button) return; 

        event.preventDefault();

        const variantId = button.getAttribute("data-variant-id");
        const crsfTokenElement = document.querySelector(
            'meta[name="csrf-token"]',
        );

        if (!crsfTokenElement) {
            console.error("CSRF token meta tag is missing!");
            return;
        }
        const crsfToken = crsfTokenElement.getAttribute("content");
        fetch("/cart/add", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": crsfToken,
                Accept: "application/json",
            },
            body: JSON.stringify({
                variant_id: variantId,
                quantity: 1,
            }),
        })
            .then((response) => {
                if (!response.ok) {
                    return response.json().then((err) => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    const cartCountElement =
                        document.getElementById("cart-count");
                    if (cartCountElement) {
                        cartCountElement.innerText =
                            Number(cartCountElement.innerText) + 1;

                        cartCountElement.style.transition = "transform 0.2s";
                        cartCountElement.style.transform = "scale(1.3)";
                        setTimeout(() => {
                            cartCountElement.style.transform = "scale(1)";
                        }, 300);
                    }
                    console.log(data.message);
                }
            })
            .catch((error) => {
                console.error("Error Details:", error);
                alert(error.message || "حدث خطأ أثناء إضافة المنتج.");
            });
    });
});
