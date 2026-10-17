/**
 * Forcha Sport · JavaScript mínimo del frontend.
 *
 * El sitio es mayormente server-rendered; aquí solo vive la interacción
 * que no necesita un framework: menú móvil, buscador y acordeón FAQ.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Menú móvil del header
    const menuButton = document.querySelector('[data-mobile-menu-button]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Buscador del header
    const searchButton = document.querySelector('[data-search-toggle]');
    const searchForm = document.querySelector('[data-search-form]');

    if (searchButton && searchForm) {
        searchButton.addEventListener('click', () => {
            searchForm.classList.toggle('hidden');
            searchForm.querySelector('input')?.focus();
        });
    }

    // Sidebar off-canvas del panel de administración (móvil)
    const adminSidebar = document.querySelector('[data-admin-sidebar]');
    const adminBackdrop = document.querySelector('[data-admin-backdrop]');

    if (adminSidebar && adminBackdrop) {
        const toggleButton = document.querySelector('[data-admin-toggle]');
        const setExpanded = (expanded) => {
            if (toggleButton) toggleButton.setAttribute('aria-expanded', String(expanded));
        };
        const openAdminMenu = () => {
            adminSidebar.classList.remove('-translate-x-full');
            adminBackdrop.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            setExpanded(true);
        };
        const closeAdminMenu = () => {
            adminSidebar.classList.add('-translate-x-full');
            adminBackdrop.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            setExpanded(false);
        };

        document.querySelector('[data-admin-toggle]')?.addEventListener('click', () => {
            const isOpen = !adminSidebar.classList.contains('-translate-x-full');
            if (isOpen) {
                closeAdminMenu();
            } else {
                openAdminMenu();
            }
        });
        document.querySelector('[data-admin-close]')?.addEventListener('click', closeAdminMenu);
        adminBackdrop.addEventListener('click', closeAdminMenu);

        // Cerrar con la tecla Escape (accesibilidad en móvil)
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !adminSidebar.classList.contains('-translate-x-full')) {
                closeAdminMenu();
            }
        });

        // Al pasar a escritorio, restaurar el layout normal
        window.matchMedia('(min-width: 1024px)').addEventListener('change', (event) => {
            if (event.matches) closeAdminMenu();
        });
    }

    // Contador de cantidad del carrito (botones +/-)
    document.querySelectorAll('[data-qty-minus]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = button.parentElement.querySelector('input');
            if (!input) return;
            input.value = Math.max(0, Number.parseInt(input.value, 10) - 1);
        });
    });

    document.querySelectorAll('[data-qty-plus]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = button.parentElement.querySelector('input');
            if (!input) return;
            const max = Number.parseInt(input.max ?? '99', 10);
            input.value = Math.min(max, Number.parseInt(input.value, 10) + 1);
        });
    });
});
