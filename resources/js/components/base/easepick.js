document.addEventListener("alpine:init", () => {
    Alpine.directive("easepick", (el, { expression }, { evaluate }) => {
        if (document.createElement(el.tagName).constructor === HTMLElement)
            return;

        const params = evaluate(expression);
        const options = {
            format: "D MMM, YYYY",
            plugins: [],
            css: [el.getAttribute("data-css")],
            ...params,
        };

        // Safe function to initialize easepick
        const safeEasepick = () => {
            if (typeof window.EasepickBundle !== 'undefined') {
                if (params.multipleMode) {
                    options.plugins = [...options.plugins, window.EasepickBundle.RangePlugin];
                    options.RangePlugin = {
                        tooltip: true,
                    };
                }

                if (!el.value) {
                    let date = window.dayjs().format(options.format);
                    date += params.multipleMode
                        ? " - " + window.dayjs().add(1, "month").format(options.format)
                        : "";

                    el.value = date;
                }

                new window.EasepickBundle.DateTimePicker(options);
                return true;
            }
            return false;
        };

        // Try to initialize immediately
        if (!safeEasepick()) {
            // If not available, poll for it
            let attempts = 0;
            const maxAttempts = 50; // 5 seconds max
            
            const pollForEasepick = () => {
                attempts++;
                if (safeEasepick()) {
                    return;
                }
                
                if (attempts < maxAttempts) {
                    setTimeout(pollForEasepick, 100);
                } else {
                    console.warn('Easepick could not be initialized after 5 seconds');
                }
            };
            
            pollForEasepick();
        }
    });
});
