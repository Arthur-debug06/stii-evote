import { slideDown, slideUp } from "../utils/helper";

document.addEventListener("alpine:init", () => {
    const sideMenuCompact = {
        compactMenu: true,
        compactMenuOnHover: false,
        mobileMenuOpen: false,
        toggleCompactMenu() {
            this.compactMenu = !this.compactMenu;
            localStorage.setItem("compactMenu", this.compactMenu);
        },
    };

    const darkMode = {
        getDarkMode() {
            const darkMode = localStorage.getItem("darkMode");
            if (darkMode === null) {
                return "inactive";
            } else if (darkMode === "system") {
                return window.matchMedia("(prefers-color-scheme:dark)").matches
                    ? "active"
                    : "inactive";
            } else {
                return darkMode;
            }
        },
        toggleDarkMode(value) {
            localStorage.setItem("darkMode", value);
            this.applyDarkMode();
        },
        applyDarkMode() {
            if (this.getDarkMode() === "active") {
                document.querySelectorAll("html")[0].classList.add("dark");
            } else {
                document.querySelectorAll("html")[0].classList.remove("dark");
            }
        },
    };

    const themeColor = {
        getThemeColor() {
            const themeColor = localStorage.getItem("themeColor");
            if (themeColor === null) {
                return "default";
            } else {
                return themeColor;
            }
        },
        setThemeColor(value) {
            localStorage.setItem("themeColor", value);
            this.applyThemeColor();
        },
        applyThemeColor() {
            document.querySelectorAll("html")[0].dataset.theme =
                this.getThemeColor();
        },
    };

    const topMenu = {
        mobileMenuOpen: false,
    };

    Alpine.data("layoutState", () => {
        return {
            ...sideMenuCompact,
            ...themeColor,
            ...darkMode,
            ...topMenu,
            init() {
                const compactMenu = localStorage.getItem("compactMenu");
                if (compactMenu === null || compactMenu == "false") {
                    this.compactMenu = false;
                }

                window.onresize = () => {
                    if (window.innerWidth <= 1600) {
                        localStorage.setItem("compactMenu", "true");
                        this.compactMenu = true;
                    }
                };

                this.applyDarkMode();
                this.applyThemeColor();
            },
        };
    });

    Alpine.data("sideMenuDropdown", ({ open = false } = {}) => ({
        open,
        toggle(e) {
            const el = e.currentTarget.parentNode.querySelector("ul");

            if (el) {
                if (this.open) {
                    slideUp(el, 300, () => {
                        this.open = !this.open;
                    });
                } else {
                    slideDown(el, 300, () => {
                        this.open = !this.open;
                    });
                }
            } else {
                window.location.href = e.currentTarget.href;
            }
        },
    }));

    Alpine.data("topMenuDropdown", ({ open = false } = {}) => ({
        open,
        toggle(e) {
            const el = e.currentTarget.parentNode.querySelector("ul");

            if (el) {
                if (this.open) {
                    slideUp(el, 300, () => {
                        this.open = !this.open;
                    });
                } else {
                    slideDown(el, 300, () => {
                        this.open = !this.open;
                    });
                }
            } else {
                window.location.href = e.currentTarget.href;
            }
        },
    }));

    Alpine.directive("scroll", (el, { expression }, { evaluate }) => {
        const simpleBarEl = new SimpleBar(el);

        simpleBarEl.getScrollElement().scrollTop =
            el
                .querySelectorAll(".side-menu__link--active")[0]
                .getBoundingClientRect().top - 200;
    });
});
