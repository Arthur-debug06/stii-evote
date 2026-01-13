import Alpine from "alpinejs";
import focus from "@alpinejs/focus";
import collapse from "@alpinejs/collapse";

window.Alpine = Alpine;

document.addEventListener("DOMContentLoaded", function () {
    Alpine.plugin(collapse);
    Alpine.plugin(focus);
    Alpine.start();
});
