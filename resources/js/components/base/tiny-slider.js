document.addEventListener("alpine:init", () => {
    Alpine.directive("tiny-slider", (el, { expression }, { evaluate }) => {
        const params = evaluate(expression);
        el.tns = tns({
            container: el,
            mouseDrag: true,
            autoplay: true,
            controls: false,
            center: true,
            items: 1,
            nav: false,
            speed: 500,
            ...params,
        });
    });
});
