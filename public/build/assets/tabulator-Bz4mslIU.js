document.addEventListener("alpine:init",()=>{Alpine.directive("tabulator",(i,{expression:l},{evaluate:a})=>{a(l);const r=new Tabulator(i,{ajaxURL:"https://midone-api.vercel.app/",paginationMode:"remote",filterMode:"remote",sortMode:"remote",printAsHtml:!0,printStyled:!0,pagination:!0,paginationSize:10,paginationSizeSelector:[10,20,30,40],layout:"fitColumns",responsiveLayout:"collapse",placeholder:"No matching records found",columns:[{title:"",formatter:"responsiveCollapse",width:40,minWidth:30,hozAlign:"center",resizable:!1,headerSort:!1},{title:"PRODUCT NAME",minWidth:200,responsive:0,field:"name",vertAlign:"middle",print:!1,download:!1,formatter(e){const t=e.getData();return`
                            <div>
                                <div class="font-medium whitespace-nowrap">${t.name}</div>
                                <div class="text-xs opacity-70 whitespace-nowrap">${t.category}</div>
                            </div>`}},{title:"IMAGES",minWidth:200,field:"images",hozAlign:"center",headerHozAlign:"center",vertAlign:"middle",print:!1,download:!1,formatter(e){const t=e.getData();return`
                            <div class="flex">
                                <div class="border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] bg-background">
                                    <img
                                        class="absolute top-0 size-full object-cover"
                                        src="${t.images[0]}"
                                        alt="Midone - Admin Dashboard Template"
                                    />
                                </div>
                                <div class="border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] bg-background -ms-5">
                                    <img
                                        class="absolute top-0 size-full object-cover"
                                        src="${t.images[1]}"
                                        alt="Midone - Admin Dashboard Template"
                                    />
                                </div>
                                <div class="border-(--color)/5 block relative size-11 flex-none overflow-hidden rounded-full border-3 ring-1 ring-(--color)/25 [--color:var(--color-primary)] bg-background -ms-5">
                                    <img
                                        class="absolute top-0 size-full object-cover"
                                        src="${t.images[2]}"
                                        alt="Midone - Admin Dashboard Template"
                                    />
                                </div>
                            </div>`}},{title:"REMAINING STOCK",minWidth:200,field:"remaining_stock",hozAlign:"center",headerHozAlign:"center",vertAlign:"middle",print:!1,download:!1},{title:"STATUS",minWidth:200,field:"status",hozAlign:"center",headerHozAlign:"center",vertAlign:"middle",print:!1,download:!1,formatter(e){const t=e.getData();return`
                            <div class="flex items-center justify-center ${t.status?"text-success":"text-danger"}">
                                <i data-lucide="check-square" class="size-4 me-2"></i>
                                ${t.status?"Active":"Inactive"}
                            </div>`}},{title:"ACTIONS",minWidth:200,field:"actions",responsive:1,hozAlign:"center",headerHozAlign:"center",vertAlign:"middle",print:!1,download:!1,formatter(){return`
                            <div class="flex items-center justify-center">
                                <a class="me-3 flex items-center" href="javascript:;">
                                    <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit
                                </a>
                                <a class="text-danger flex items-center" href="javascript:;">
                                    <i data-lucide="trash" class="w-4 h-4 mr-1"></i> Delete
                                </a>
                            </div>`}},{title:"PRODUCT NAME",field:"name",visible:!1,print:!0,download:!0},{title:"CATEGORY",field:"category",visible:!1,print:!0,download:!0},{title:"REMAINING STOCK",field:"remaining_stock",visible:!1,print:!0,download:!0},{title:"STATUS",field:"status",visible:!1,print:!0,download:!0,formatterPrint(e){return e.getValue()?"Active":"Inactive"}},{title:"IMAGE 1",field:"images",visible:!1,print:!0,download:!0,formatterPrint(e){return e.getValue()[0]}},{title:"IMAGE 2",field:"images",visible:!1,print:!0,download:!0,formatterPrint(e){return e.getValue()[1]}},{title:"IMAGE 3",field:"images",visible:!1,print:!0,download:!0,formatterPrint(e){return e.getValue()[2]}}]});r.on("renderComplete",()=>{createIcons({icons,attrs:{"stroke-width":1.5},nameAttr:"data-lucide"})}),i.tabulator=r})});
