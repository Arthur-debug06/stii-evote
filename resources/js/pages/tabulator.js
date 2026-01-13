document.addEventListener("alpine:init", () => {
    Alpine.directive("tabulator", (el, { expression }, { evaluate }) => {
        const params = evaluate(expression);

        // Setup Tabulator
        const tabulator = new Tabulator(el, {
            ajaxURL: "https://midone-api.vercel.app/",
            paginationMode: "remote",
            filterMode: "remote",
            sortMode: "remote",
            printAsHtml: true,
            printStyled: true,
            pagination: true,
            paginationSize: 10,
            paginationSizeSelector: [10, 20, 30, 40],
            layout: "fitColumns",
            responsiveLayout: "collapse",
            placeholder: "No matching records found",
            columns: [
                {
                    title: "",
                    formatter: "responsiveCollapse",
                    width: 40,
                    minWidth: 30,
                    hozAlign: "center",
                    resizable: false,
                    headerSort: false,
                },

                // For HTML table
                {
                    title: "PRODUCT NAME",
                    minWidth: 200,
                    responsive: 0,
                    field: "name",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell) {
                        const response = cell.getData();
                        return `
                            <div>
                                <div class="font-medium whitespace-nowrap">${response.name}</div>
                                <div class="text-xs opacity-70 whitespace-nowrap">${response.category}</div>
                            </div>`;
                    },
                },
                {
                    title: "IMAGES",
                    minWidth: 200,
                    field: "images",
                    hozAlign: "center",
                    headerHozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell) {
                        const response = cell.getData();
                        return `
                            <div class="flex">
                                <div class="border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] bg-background">
                                    <img
                                        class="absolute top-0 size-full object-cover"
                                        src="${response.images[0]}"
                                        alt="Midone - Admin Dashboard Template"
                                    />
                                </div>
                                <div class="border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] bg-background -ms-5">
                                    <img
                                        class="absolute top-0 size-full object-cover"
                                        src="${response.images[1]}"
                                        alt="Midone - Admin Dashboard Template"
                                    />
                                </div>
                                <div class="border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] bg-background -ms-5">
                                    <img
                                        class="absolute top-0 size-full object-cover"
                                        src="${response.images[2]}"
                                        alt="Midone - Admin Dashboard Template"
                                    />
                                </div>
                            </div>`;
                    },
                },
                {
                    title: "REMAINING STOCK",
                    minWidth: 200,
                    field: "remaining_stock",
                    hozAlign: "center",
                    headerHozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                },
                {
                    title: "STATUS",
                    minWidth: 200,
                    field: "status",
                    hozAlign: "center",
                    headerHozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter(cell) {
                        const response = cell.getData();
                        return `
                            <div class="flex items-center justify-center ${
                                response.status ? "text-success" : "text-danger"
                            }">
                                <i data-lucide="check-square" class="size-4 me-2"></i>
                                ${response.status ? "Active" : "Inactive"}
                            </div>`;
                    },
                },
                {
                    title: "ACTIONS",
                    minWidth: 200,
                    field: "actions",
                    responsive: 1,
                    hozAlign: "center",
                    headerHozAlign: "center",
                    vertAlign: "middle",
                    print: false,
                    download: false,
                    formatter() {
                        return `
                            <div class="flex items-center justify-center">
                                <a class="me-3 flex items-center" href="javascript:;">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                                </a>
                                <a class="text-danger flex items-center" href="javascript:;">
                                    <i data-lucide="trash" class="w-4 h-4 mr-1"></i> Delete
                                </a>
                            </div>`;
                    },
                },

                // For print format
                {
                    title: "PRODUCT NAME",
                    field: "name",
                    visible: false,
                    print: true,
                    download: true,
                },
                {
                    title: "CATEGORY",
                    field: "category",
                    visible: false,
                    print: true,
                    download: true,
                },
                {
                    title: "REMAINING STOCK",
                    field: "remaining_stock",
                    visible: false,
                    print: true,
                    download: true,
                },
                {
                    title: "STATUS",
                    field: "status",
                    visible: false,
                    print: true,
                    download: true,
                    formatterPrint(cell) {
                        return cell.getValue() ? "Active" : "Inactive";
                    },
                },
                {
                    title: "IMAGE 1",
                    field: "images",
                    visible: false,
                    print: true,
                    download: true,
                    formatterPrint(cell) {
                        return cell.getValue()[0];
                    },
                },
                {
                    title: "IMAGE 2",
                    field: "images",
                    visible: false,
                    print: true,
                    download: true,
                    formatterPrint(cell) {
                        return cell.getValue()[1];
                    },
                },
                {
                    title: "IMAGE 3",
                    field: "images",
                    visible: false,
                    print: true,
                    download: true,
                    formatterPrint(cell) {
                        return cell.getValue()[2];
                    },
                },
            ],
        });

        tabulator.on("renderComplete", () => {
            createIcons({
                icons,
                attrs: {
                    "stroke-width": 1.5,
                },
                nameAttr: "data-lucide",
            });
        });

        el.tabulator = tabulator;
    });
});
