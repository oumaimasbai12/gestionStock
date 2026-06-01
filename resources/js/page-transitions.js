/**
 * Page transitions for Livewire wire:navigate (body is fully swapped on each visit).
 * Uses a persisted overlay + enter animation on new content.
 */

const DURATION_MS = 300;

let navigateStartedAt = 0;

function getOverlay() {
    return document.getElementById('app-page-transition-overlay');
}

function getPageContent() {
    return document.getElementById('app-page-content');
}

function shouldAutoNavigate(link) {
    if (!link?.href) return false;
    if (link.hasAttribute('data-no-navigate')) return false;
    if (link.closest('[data-no-navigate]')) return false;
    if (link.hasAttribute('download')) return false;
    if (link.target === '_blank') return false;
    if (link.hasAttribute('wire:click')) return false;

    const href = link.getAttribute('href') ?? '';
    if (!href || href.startsWith('#') || href.startsWith('javascript:')) return false;
    if (/\/logout|\.(pdf|csv|zip|xlsx?)(\?|$)/i.test(href)) return false;

    try {
        const url = new URL(link.href, window.location.origin);
        if (url.origin !== window.location.origin) return false;
    } catch {
        return false;
    }

    return true;
}

export function patchNavigateLinks(root = document) {
    const scope = root.querySelector?.('.app-main') ?? root;
    if (!scope?.querySelectorAll) return;

    scope.querySelectorAll('a[href]').forEach((link) => {
        if (!shouldAutoNavigate(link)) return;
        if (!link.hasAttribute('wire:navigate')) {
            link.setAttribute('wire:navigate', '');
        }
        if (!link.hasAttribute('wire:navigate.hover')) {
            link.setAttribute('wire:navigate.hover', '');
        }
    });
}

function showOverlay() {
    navigateStartedAt = Date.now();
    getOverlay()?.classList.add('is-visible');
}

function hideOverlayThenAnimateContent() {
    const overlay = getOverlay();
    const elapsed = Date.now() - navigateStartedAt;
    const wait = Math.max(0, DURATION_MS - elapsed);

    setTimeout(() => {
        overlay?.classList.remove('is-visible');

        const content = getPageContent();
        if (!content) return;

        content.classList.remove('page-is-entering');
        void content.offsetWidth;
        content.classList.add('page-is-entering');

        const clear = () => content.classList.remove('page-is-entering');
        content.addEventListener('animationend', clear, { once: true });
        setTimeout(clear, DURATION_MS + 50);
    }, wait);
}

function onNavigating() {
    showOverlay();
}

function onNavigated() {
    patchNavigateLinks();
    hideOverlayThenAnimateContent();
}

function bindPageTransitions() {
    document.addEventListener('livewire:navigating', onNavigating);
    document.addEventListener('livewire:navigated', onNavigated);
    document.addEventListener('alpine:navigating', onNavigating);
    document.addEventListener('alpine:navigated', onNavigated);
}

function runInitialEnterAnimation() {
    const content = getPageContent();
    if (!content) return;

    content.classList.add('page-is-entering');
    const clear = () => content.classList.remove('page-is-entering');
    content.addEventListener('animationend', clear, { once: true });
    setTimeout(clear, DURATION_MS + 50);
}

function bindNavigateClickFallback() {
    document.addEventListener(
        'click',
        (e) => {
            const link = e.target.closest('a');
            if (!link || !shouldAutoNavigate(link)) return;
            if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
            if (e.defaultPrevented) return;

            const livewire = window.Livewire;
            if (!livewire?.navigate) return;

            e.preventDefault();
            livewire.navigate(link.href);
        },
        true
    );
}

document.addEventListener('livewire:init', () => {
    bindPageTransitions();
    bindNavigateClickFallback();
    patchNavigateLinks();
});

document.addEventListener('DOMContentLoaded', () => {
    patchNavigateLinks();
    runInitialEnterAnimation();
});
