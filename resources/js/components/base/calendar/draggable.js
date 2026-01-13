document.addEventListener("alpine:init", () => {
    Alpine.directive(
        "calendar-draggable",
        (el, { expression }, { evaluate }) => {
            new Draggable(el, {
                itemSelector: "[data-event]",
                eventData: function (el) {
                    return {
                        title: el.querySelectorAll("[data-title]")[0].innerText,
                        duration: {
                            days: parseInt(
                                el.querySelectorAll("[data-days]")[0].innerText
                            ),
                        },
                    };
                },
            });
        }
    );
});
