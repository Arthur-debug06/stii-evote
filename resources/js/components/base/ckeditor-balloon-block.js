document.addEventListener("alpine:init", () => {
    Alpine.directive("editor", (el, { expression }, { evaluate }) => {
        if (!el.classList.contains("ck")) {
            CkeditorBalloonBlock.create(el)
                .then((editor) => {
                    el.parentElement.classList.add("prose", "max-w-full");
                })
                .catch((error) => {
                    console.error(error);
                });
        }
    });
});
