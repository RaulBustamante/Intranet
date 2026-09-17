import { initPalette } from './palette';

/* ==========================================================================
   Ariel Hub — interacciones de la carcasa
   Sin framework: son cuatro comportamientos y no justifican una dependencia.
   ========================================================================== */

/* ---- Tema claro / oscuro (UX-05) ---------------------------------------- */
function initTheme() {
    const root = document.documentElement;

    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const dark = root.classList.toggle('dark');
            try {
                localStorage.setItem('ah-theme', dark ? 'dark' : 'light');
            } catch (e) { /* modo privado: se pierde la preferencia, no pasa nada */ }
        });
    });

    // Si el usuario nunca eligió explícitamente, seguimos al sistema
    try {
        if (!localStorage.getItem('ah-theme')) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                root.classList.toggle('dark', e.matches);
            });
        }
    } catch (e) {}
}

/* ---- Menús desplegables -------------------------------------------------- */
function initDropdowns() {
    const cerrarTodos = (excepto) => {
        document.querySelectorAll('[data-dropdown]').forEach((d) => {
            if (d === excepto) return;
            d.querySelector('[data-dropdown-panel]')?.classList.add('hidden');
            d.querySelector('[data-dropdown-trigger]')?.setAttribute('aria-expanded', 'false');
        });
    };

    document.querySelectorAll('[data-dropdown]').forEach((drop) => {
        const trigger = drop.querySelector('[data-dropdown-trigger]');
        const panel = drop.querySelector('[data-dropdown-panel]');
        if (!trigger || !panel) return;

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            cerrarTodos(drop);
            const abierto = panel.classList.toggle('hidden');
            trigger.setAttribute('aria-expanded', abierto ? 'false' : 'true');
        });
    });

    document.addEventListener('click', () => cerrarTodos(null));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') cerrarTodos(null);
    });
}

/* ---- Menú móvil ---------------------------------------------------------- */
function initMobileMenu() {
    const btn = document.querySelector('[data-mobile-toggle]');
    const panel = document.querySelector('[data-mobile-panel]');
    if (!btn || !panel) return;

    btn.addEventListener('click', () => {
        const oculto = panel.classList.toggle('hidden');
        btn.setAttribute('aria-expanded', oculto ? 'false' : 'true');
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initDropdowns();
    initMobileMenu();
    initPalette();
});
