document.addEventListener("alpine:init", () => {
    Alpine.directive("tippy", (el, { expression }, { evaluate }) => {
        const params = evaluate(expression);
        
        // Safe function to initialize tippy
        const safeTippy = () => {
            if (typeof window.tippy !== 'undefined') {
                if (params.content.length) {
                    window.tippy(el, {
                        content: "",
                        render(instance) {
                            const popper = document.createElement("div");
                            const box = document.createElement("div");

                            popper.appendChild(box);

                            box.className =
                                "backdrop-blur-xl rounded-lg border px-2.5 py-0.5 bg-(--color)/20 border-(--color)/60 text-(--color) [--color:var(--color-primary)]";
                            box.textContent = instance.props.content;

                            return {
                                popper,
                            };
                        },
                        ...params,
                    });
                }
                return true;
            }
            return false;
        };

        // Try to initialize immediately
        if (!safeTippy()) {
            // If not available, poll for it
            let attempts = 0;
            const maxAttempts = 50; // 5 seconds max
            
            const pollForTippy = () => {
                attempts++;
                if (safeTippy()) {
                    return;
                }
                
                if (attempts < maxAttempts) {
                    setTimeout(pollForTippy, 100);
                } else {
                    console.warn('Tippy could not be initialized after 5 seconds');
                }
            };
            
            pollForTippy();
        }
    });
});
