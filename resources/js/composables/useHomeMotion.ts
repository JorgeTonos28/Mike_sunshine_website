import { onMounted, onUnmounted, type Ref } from "vue";

export function useHomeMotion(root: Ref<HTMLElement | undefined>) {
    let observer: IntersectionObserver | undefined;
    let preference: MediaQueryList | undefined;

    function reveal(element: Element) {
        element.setAttribute("data-reveal", "visible");
        observer?.unobserve(element);
    }

    function stopMotion() {
        if (!preference?.matches) return;
        root.value?.removeAttribute("data-motion-ready");
        root.value?.querySelectorAll("[data-reveal]").forEach(reveal);
        observer?.disconnect();
    }

    function revealFocused(event: FocusEvent) {
        if (event.target instanceof Element) {
            const pending = event.target.closest('[data-reveal="pending"]');
            if (pending) reveal(pending);
        }
    }

    onMounted(() => {
        const page = root.value;
        if (!page) return;
        preference = window.matchMedia("(prefers-reduced-motion: reduce)");
        if (preference.matches || !("IntersectionObserver" in window)) return;

        observer = new IntersectionObserver(
            (entries) => {
                for (const entry of entries) {
                    if (entry.isIntersecting) reveal(entry.target);
                }
            },
            { threshold: 0.08, rootMargin: "0px 0px -24px 0px" },
        );
        // Content is visible by default; only an installed observer enables hiding.
        page.querySelectorAll<HTMLElement>("[data-reveal]").forEach(
            (element) => {
                if (element.getBoundingClientRect().bottom <= 0) {
                    reveal(element);
                } else {
                    element.setAttribute("data-reveal", "pending");
                    observer?.observe(element);
                }
            },
        );
        page.setAttribute("data-motion-ready", "");
        preference.addEventListener("change", stopMotion);
        page.addEventListener("focusin", revealFocused);
    });

    onUnmounted(() => {
        observer?.disconnect();
        preference?.removeEventListener("change", stopMotion);
        root.value?.removeEventListener("focusin", revealFocused);
    });
}
