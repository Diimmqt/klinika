export function initLandingPage() {
    if (!document.body || document.body.dataset.page !== 'landing') {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // 1. Mobile navigation drawer
    const mobileToggle = document.getElementById('landing-mobile-toggle');
    const mobileMenu = document.getElementById('landing-mobile-menu');
    const iconOpen = document.getElementById('landing-icon-open');
    const iconClose = document.getElementById('landing-icon-close');

    const setMenuOpen = (isOpen) => {
        if (!mobileToggle || !mobileMenu) return;
        mobileToggle.setAttribute('aria-expanded', String(isOpen));
        mobileMenu.classList.toggle('hidden', !isOpen);
        if (iconOpen && iconClose) {
            iconOpen.classList.toggle('hidden', isOpen);
            iconClose.classList.toggle('hidden', !isOpen);
        }
    };

    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', () => {
            const nextState = mobileToggle.getAttribute('aria-expanded') !== 'true';
            setMenuOpen(nextState);
        });

        mobileMenu.querySelectorAll('a[href^="#"]').forEach((link) => {
            link.addEventListener('click', () => setMenuOpen(false));
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && mobileToggle.getAttribute('aria-expanded') === 'true') {
                setMenuOpen(false);
                mobileToggle.focus();
            }
        });
    }

    // 2. Sticky navbar subtle elevation & active section tracking
    const navbar = document.getElementById('landing-navbar');
    const navLinks = Array.from(document.querySelectorAll('[data-nav-link]'));
    const trackedSections = navLinks
        .map((link) => {
            const id = link.getAttribute('href')?.replace('#', '');
            const el = id ? document.getElementById(id) : null;
            return el ? { link, el } : null;
        })
        .filter(Boolean);

    const updateNavbarScrollState = () => {
        if (navbar) {
            const scrolled = window.scrollY > 12;
            navbar.classList.toggle('shadow-[0_4px_24px_rgba(16,61,64,0.06)]', scrolled);
            navbar.classList.toggle('bg-white/95', scrolled);
            navbar.classList.toggle('bg-white', !scrolled);
        }

        if (trackedSections.length === 0) return;
        const scrollPosition = window.scrollY + 130;
        let activeEntry = trackedSections[0];

        for (const entry of trackedSections) {
            if (entry.el.offsetTop <= scrollPosition) {
                activeEntry = entry;
            }
        }

        navLinks.forEach((link) => {
            const isActive = link === activeEntry.link;
            link.classList.toggle('text-[#087F83]', isActive);
            link.classList.toggle('text-[#182B35]', !isActive);
            if (isActive) {
                link.setAttribute('aria-current', 'location');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    };

    window.addEventListener('scroll', updateNavbarScrollState, { passive: true });
    updateNavbarScrollState();

    // 3. Interactive Dashboard Preview role tabs
    const previewTabs = Array.from(document.querySelectorAll('[data-preview-tab]'));
    const previewPanels = Array.from(document.querySelectorAll('[data-preview-panel]'));
    const previewBreadcrumb = document.getElementById('preview-browser-label');

    const activatePreviewTab = (targetKey, focusTab = false) => {
        previewTabs.forEach((tab) => {
            const isSelected = tab.dataset.previewTab === targetKey;
            tab.setAttribute('aria-selected', String(isSelected));
            tab.setAttribute('tabindex', isSelected ? '0' : '-1');

            tab.classList.toggle('bg-[#087F83]', isSelected);
            tab.classList.toggle('text-white', isSelected);
            tab.classList.toggle('border-[#087F83]', isSelected);
            tab.classList.toggle('shadow-sm', isSelected);

            tab.classList.toggle('bg-white', !isSelected);
            tab.classList.toggle('text-[#182B35]', !isSelected);
            tab.classList.toggle('border-[#DDE8E8]', !isSelected);

            if (isSelected && previewBreadcrumb && tab.dataset.browserTitle) {
                previewBreadcrumb.textContent = tab.dataset.browserTitle;
            }
            if (isSelected && focusTab) {
                tab.focus();
            }
        });

        previewPanels.forEach((panel) => {
            const isMatch = panel.dataset.previewPanel === targetKey;
            panel.classList.toggle('hidden', !isMatch);
        });
    };

    previewTabs.forEach((tab, index) => {
        tab.addEventListener('click', () => {
            activatePreviewTab(tab.dataset.previewTab);
        });

        tab.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
                event.preventDefault();
                const delta = event.key === 'ArrowRight' ? 1 : -1;
                const nextIndex = (index + delta + previewTabs.length) % previewTabs.length;
                activatePreviewTab(previewTabs[nextIndex].dataset.previewTab, true);
            }
        });
    });

    // 4. Accessible FAQ accordion
    const faqButtons = Array.from(document.querySelectorAll('[data-faq-trigger]'));

    faqButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('aria-controls');
            const panel = targetId ? document.getElementById(targetId) : null;
            const icon = button.querySelector('[data-faq-chevron]');
            if (!panel) return;

            const isCurrentlyOpen = button.getAttribute('aria-expanded') === 'true';
            const nextOpen = !isCurrentlyOpen;

            button.setAttribute('aria-expanded', String(nextOpen));
            panel.setAttribute('data-open', String(nextOpen));
            if (icon) {
                icon.classList.toggle('rotate-180', nextOpen);
            }
        });
    });

    // 5. Scroll reveal via IntersectionObserver
    const revealNodes = Array.from(document.querySelectorAll('[data-reveal]'));
    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        revealNodes.forEach((node) => node.classList.add('is-visible'));
    } else {
        const observer = new IntersectionObserver(
            (entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -36px 0px' }
        );

        revealNodes.forEach((node) => observer.observe(node));
    }
}
