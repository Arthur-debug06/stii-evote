document.addEventListener("alpine:init", () => {
    Alpine.directive("lucide", (el, { expression }, { evaluate }) => {
        const params = evaluate(expression);
        const dataAttr = `data-${params.id}`;
        const icon = params.icon;
        const iconElement = document.createElement("i");

        iconElement.className = el.className;
        iconElement.setAttribute(dataAttr, icon);
        el.insertAdjacentElement("afterend", iconElement);
        el.parentNode.removeChild(el);

        // Safe function to initialize icons
        const safeCreateIcons = () => {
            if (typeof window.createIcons !== 'undefined' && typeof window.icons !== 'undefined') {
                window.createIcons({
                    icons: window.icons,
                    "stroke-width": 1.5,
                    nameAttr: dataAttr,
                });
                return true;
            }
            return false;
        };

        // Try to initialize immediately
        if (!safeCreateIcons()) {
            // If not available, poll for it
            let attempts = 0;
            const maxAttempts = 50; // 5 seconds max
            
            const pollForIcons = () => {
                attempts++;
                if (safeCreateIcons()) {
                    return;
                }
                
                if (attempts < maxAttempts) {
                    setTimeout(pollForIcons, 100);
                } else {
                    console.warn('Lucide icons could not be initialized after 5 seconds');
                }
            };
            
            pollForIcons();
        }
    });
});
