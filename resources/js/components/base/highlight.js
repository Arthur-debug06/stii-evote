document.addEventListener("alpine:init", () => {
    Alpine.directive("highlight", (el, { expression }, { evaluate }) => {
        const highlightedCode = hljs.highlight(jsBeautify.html(el.innerHTML), {
            language: "html",
        }).value;

        el.innerHTML = highlightedCode;
    });
});
