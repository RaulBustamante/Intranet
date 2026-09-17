/* ---- Paleta de comandos ⌘K (SRCH-01, SRCH-05) ---------------------------
   Busca EN VIVO contra /buscar mientras se escribe. Resultados reales de
   personas, documentos, boletines, enlaces y acciones ejecutables. */
export function initPalette() {
    const palette = document.querySelector('[data-palette]');
    if (!palette) return;

    const input = palette.querySelector('[data-palette-input]');
    const results = palette.querySelector('[data-palette-results]');
    const labels = window.ahPaletteLabels || {};
    let activo = 0;
    let visibles = [];
    let debounce = null;

    function abrir() {
        palette.hidden = false;
        document.body.style.overflow = 'hidden';
        input.value = '';
        results.innerHTML = hint();
        input.focus();
    }
    function cerrar() {
        palette.hidden = true;
        document.body.style.overflow = '';
    }

    function hint() {
        return '<p class="px-3 py-8 text-center text-sm" style="color: var(--ink-500)">' +
               escapeHtml(labels.hint || '') + '</p>';
    }

    async function buscar(termino) {
        const t = termino.trim();
        if (t.length < 2) { visibles = []; results.innerHTML = hint(); return; }

        try {
            const res = await fetch('/buscar?q=' + encodeURIComponent(t), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!res.ok) throw new Error('search failed');
            const data = await res.json();

            visibles = [];
            (data.results || []).forEach((r) => visibles.push(r));
            (data.actions || []).forEach((a) => visibles.push(a));
            if (data.ai && data.ai.enabled) {
                visibles.push({ group: 'ai', title: (labels.ask || 'Ask') + ': "' + t + '"', url: '#', icon: 'search' });
            }
            activo = 0;
            render(t);
        } catch (e) {
            results.innerHTML = '<p class="px-3 py-8 text-center text-sm" style="color: var(--ink-500)">…</p>';
        }
    }

    function render(termino) {
        if (!visibles.length) {
            results.innerHTML = '<p class="px-3 py-8 text-center text-sm" style="color: var(--ink-500)">' +
                (labels.noResults || '').replace('__TERM__', escapeHtml(termino)) + '</p>';
            return;
        }
        let html = '';
        let grupoPrevio = null;
        visibles.forEach((item, i) => {
            if (item.group !== grupoPrevio) {
                grupoPrevio = item.group;
                html += '<p class="ah-eyebrow px-2.5 pb-1 pt-2.5">' +
                        escapeHtml(labels[item.group] || item.group) + '</p>';
            }
            html +=
                '<a href="' + escapeHtml(item.url) + '" data-idx="' + i + '"' +
                (item.external ? ' target="_blank" rel="noopener"' : '') +
                ' class="flex items-center gap-2.5 rounded-md px-2.5 py-2 text-sm no-underline" style="color: var(--ink-700)">' +
                '<span class="truncate">' + escapeHtml(item.title) + '</span>' +
                (item.subtitle ? '<span class="ml-auto truncate text-xs" style="color: var(--ink-500)">' + escapeHtml(item.subtitle) + '</span>' : '') +
                '</a>';
        });
        results.innerHTML = html;
        marcar();
    }

    function marcar() {
        results.querySelectorAll('[data-idx]').forEach((el, i) => {
            const on = i === activo;
            el.style.background = on ? 'var(--paper-3)' : '';
            el.style.color = on ? 'var(--ink-900)' : 'var(--ink-700)';
            if (on) el.scrollIntoView({ block: 'nearest' });
        });
    }

    function escapeHtml(s) {
        const d = document.createElement('div');
        d.textContent = s == null ? '' : String(s);
        return d.innerHTML;
    }

    document.querySelectorAll('[data-palette-open]').forEach((b) => b.addEventListener('click', abrir));
    palette.querySelector('[data-palette-close]')?.addEventListener('click', cerrar);
    palette.querySelector('[data-palette-backdrop]')?.addEventListener('click', cerrar);
    input?.addEventListener('input', (e) => {
        clearTimeout(debounce);
        const v = e.target.value;
        debounce = setTimeout(() => buscar(v), 200);
    });

    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
            e.preventDefault();
            palette.hidden ? abrir() : cerrar();
            return;
        }
        if (palette.hidden) return;
        if (e.key === 'Escape') { e.preventDefault(); cerrar(); }
        if (e.key === 'ArrowDown') { e.preventDefault(); activo = Math.min(activo + 1, visibles.length - 1); marcar(); }
        if (e.key === 'ArrowUp') { e.preventDefault(); activo = Math.max(activo - 1, 0); marcar(); }
        if (e.key === 'Enter' && visibles[activo]) { e.preventDefault(); window.location.href = visibles[activo].url; }
    });
}
