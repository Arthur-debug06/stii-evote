import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS Themes
                "resources/css/themes/enigma/side-menu.css",
                "resources/css/themes/enigma/top-menu.css",
                "resources/css/themes/icewall/side-menu.css",
                "resources/css/themes/icewall/top-menu.css",
                "resources/css/themes/rubick/side-menu.css",
                "resources/css/themes/rubick/top-menu.css",
                "resources/css/themes/tinker/side-menu.css",
                "resources/css/themes/tinker/top-menu.css",

                // CSS Vendors
                "resources/css/vendors/ckeditor.css",
                "resources/css/vendors/dropzone.css",
                "resources/css/vendors/easepick.css",
                "resources/css/vendors/full-calendar.css",
                "resources/css/vendors/highlight.css",
                "resources/css/vendors/simplebar.css",
                "resources/css/vendors/tabulator.css",
                "resources/css/vendors/tiny-slider.css",
                "resources/css/vendors/tom-select.css",
                "resources/css/vendors/vector-map.css",
                "resources/css/vendors/zoom-vanilla.css",

                // CSS General
                "resources/css/app.css",

                // JS Vendor
                "resources/js/vendors/calendar/plugins/day-grid.js",
                "resources/js/vendors/calendar/plugins/interaction.js",
                "resources/js/vendors/calendar/plugins/list.js",
                "resources/js/vendors/calendar/plugins/time-grid.js",
                "resources/js/vendors/calendar/calendar.js",
                "resources/js/vendors/ckeditor/balloon-block.js",
                "resources/js/vendors/ckeditor/balloon.js",
                "resources/js/vendors/ckeditor/classic.js",
                "resources/js/vendors/ckeditor/document.js",
                "resources/js/vendors/ckeditor/inline.js",
                "resources/js/vendors/axios.js",
                "resources/js/vendors/chartjs.js",
                "resources/js/vendors/dayjs.js",
                "resources/js/vendors/dropzone.js",
                "resources/js/vendors/easepick.js",
                "resources/js/vendors/highlight.js",
                "resources/js/vendors/image-zoom.js",
                "resources/js/vendors/lodash.js",
                "resources/js/vendors/lucide.js",
                "resources/js/vendors/popper.js",
                "resources/js/vendors/pristine.js",
                "resources/js/vendors/simplebar.js",
                "resources/js/vendors/tabulator.js",
                "resources/js/vendors/tiny-slider.js",
                "resources/js/vendors/tippy.js",
                "resources/js/vendors/tom-select.js",
                "resources/js/vendors/vector-map.js",
                "resources/js/vendors/xlsx.js",

                // JS Utils
                "resources/js/utils/helper.js",

                // JS Pages
                "resources/js/pages/tabulator.js",

                // JS Themes
                "resources/js/themes/rubick.js",
                "resources/js/themes/icewall.js",
                "resources/js/themes/tinker.js",
                "resources/js/themes/enigma.js",

                // JS Base Components
                "resources/js/components/base/calendar/calendar.js",
                "resources/js/components/base/calendar/draggable.js",
                "resources/js/components/base/chart.js",
                "resources/js/components/base/ckeditor-balloon-block.js",
                "resources/js/components/base/ckeditor-balloon.js",
                "resources/js/components/base/ckeditor-classic.js",
                "resources/js/components/base/ckeditor-document.js",
                "resources/js/components/base/ckeditor-inline.js",
                "resources/js/components/base/dropzone.js",
                "resources/js/components/base/easepick.js",
                "resources/js/components/base/highlight.js",
                "resources/js/components/base/lucide.js",
                "resources/js/components/base/tiny-slider.js",
                "resources/js/components/base/tippy.js",
                "resources/js/components/base/tom-select.js",
                "resources/js/components/base/vector-map.js",

                // JS General
                "resources/js/app.js",
            ],
            refresh: true,
        }),
    ],
});
