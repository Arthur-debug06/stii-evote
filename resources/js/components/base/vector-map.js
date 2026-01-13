document.addEventListener("alpine:init", () => {
    Alpine.directive("vector-map", (el, { expression }, { evaluate }) => {
        new jsVectorMap({
            selector: "#map",
            map: "world",
            backgroundColor:
                "color-mix(in oklch, var(--color-foreground), transparent 98%)",
            regionStyle: {
                initial: {
                    fill: "color-mix(in oklch, var(--color-foreground), transparent 82%)",
                },
            },
            markerStyle: {
                initial: {
                    r: 4,
                    strokeWidth: 15,
                    stroke: "color-mix(in oklch, var(--color-primary), transparent 60%)",
                    fill: "color-mix(in oklch, var(--color-primary), transparent 30%)",
                },
                hover: {
                    fill: "color-mix(in oklch, var(--color-primary), transparent 30%)",
                },
            },
            ...evaluate(expression),
        });
    });
});
