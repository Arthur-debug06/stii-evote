document.addEventListener("alpine:init", () => {
    Alpine.directive("chart", (el, { expression }, { evaluate }) => {
        const params = evaluate(expression);

        // Safe function to initialize chart
        const safeChart = () => {
            if (typeof window.Chart !== 'undefined') {
                const chart = new window.Chart(el, {
                    ...params,
                    ...(params.init && params.init(el)),
                });

                el.chart = chart;

                // Watch class name changes
                if (typeof watchClassChanges !== 'undefined') {
                    watchClassChanges(
                        document.querySelectorAll("html")[0],
                        (currentClassName) => {
                            chart.update();
                        }
                    );
                }
                return true;
            }
            return false;
        };

        // Try to initialize immediately
        if (!safeChart()) {
            // If not available, wait for it to be available
            const checkInterval = setInterval(() => {
                if (safeChart()) {
                    clearInterval(checkInterval);
                }
            }, 50);

            // Stop checking after 2 seconds to prevent infinite loops
            setTimeout(() => {
                clearInterval(checkInterval);
            }, 2000);
        }
    });
});
