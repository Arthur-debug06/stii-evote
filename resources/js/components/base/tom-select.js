document.addEventListener("alpine:init", () => {
    Alpine.directive("tom-select", (el, { expression }, { evaluate }) => {
        if (document.createElement(el.tagName).constructor === HTMLElement)
            return;

        const params = evaluate(expression);
        let options = {
            plugins: {
                dropdown_input: {},
            },
            ...params,
        };

        if (el.hasAttribute("multiple")) {
            options = {
                ...options,
                plugins: {
                    ...options.plugins,
                    remove_button: {
                        title: "Remove this item",
                    },
                },
                persist: false,
                create: true,
                onDelete: function (values) {
                    return confirm(
                        values.length > 1
                            ? "Are you sure you want to remove these " +
                                  values.length +
                                  " items?"
                            : 'Are you sure you want to remove "' +
                                  values[0] +
                                  '"?'
                    );
                },
            };
        }

        if (params.header) {
            options = {
                ...options,
                plugins: {
                    ...options.plugins,
                    dropdown_header: {
                        title: params.header,
                    },
                },
            };
        }

        new TomSelect(el, options);
    });
});
