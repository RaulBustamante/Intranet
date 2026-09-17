# Ariel Hub — v2

Reconstrucción de la intranet de Ariel Premium Supply. Convive con la v1 (que está en la raíz de este repositorio) sin tocarla: la v1 sigue en producción hasta el corte de la Fase 7.

- **Planeación de negocio:** `asistente-ejecutivo-trabajo/projects/intranet-v2/`
- **Fases ejecutables:** `../.planning/ROADMAP.md`
- **Estado del proyecto:** `../.planning/STATE.md`

## Cómo levantarlo en local

```bash
cd v2

# Usa el PHP de Laragon 8.4: el del PATH es XAMPP 8.2.12 y le falta intl,
# y Laragon 8.3.30 NO SIRVE (no tiene php.ini, no carga curl ni openssl).
PHP="/c/laragon/bin/php/php-8.4.24-Win32-vs17-x64/php.exe"

"$PHP" /c/ProgramData/ComposerSetup/bin/composer.phar install
npm install

"$PHP" artisan key:generate      # solo la primera vez
npm run build                     # o `npm run dev` si vas a editar estilos
"$PHP" artisan serve --port=8100
```

Abre `http://127.0.0.1:8100`.

## Stack

| Capa | Versión |
|---|---|
| Laravel | 13.31 (requiere **PHP ^8.3**) |
| Livewire | 4.4 |
| Tailwind CSS | 4 |
| Vite | 8 |
| Tipografía | Inter, auto-hospedada (sin CDN externa) |

> El plan original decía Laravel 12 + Livewire 3. Al instalar salió 13 + 4 y se aceptó a propósito: es un proyecto nuevo, no hay nada que migrar, y arrancar en una versión ya vencida no tiene sentido. Registrado en el `CHANGE_LOG.md` del proyecto.

## Qué hay construido (Fase 1)

- **Sistema de diseño Executive Light** en `resources/css/app.css`: tokens de color, tipografía, espaciado y radios, con modo claro y oscuro.
- **Layout único** en `resources/views/components/layouts/app.blade.php`. Ya no existe el header repetido 23 veces de la v1.
- **Navegación con una sola definición**: `config/navigation.php`. Cambiar una entrada la cambia en todas las páginas.
- **Bilingüe ES/EN**: `lang/es/` y `lang/en/`, con `SetLocale` resolviendo sesión → navegador → default. **No hay ni una cadena visible escrita dentro de una vista.**
- **Componentes Blade**: `icon`, `card`, `button`, `avatar`, `empty-state`, `page-header`.
- **Paleta de comandos ⌘K** con navegación por teclado y normalización de acentos ("Nuno" encuentra "Nuño"). La búsqueda real llega en la Fase 6.
- **Página de inicio personalizada** con datos de muestra, ya con la forma que tendrán los datos reales.
- **16 rutas navegables.** Las secciones sin construir muestran un marcador honesto que dice en qué fase llegan.

## La regla de color, que no es negociable

`#ED2228` sobre blanco da ~4.0:1 de contraste. **Eso no alcanza AA para texto normal.** Por lo tanto:

- `#ED2228` se usa **solo como fondo**, con texto blanco encima → `.ah-btn-primary`
- Texto y enlaces en rojo usan `#C91B21` (~5.3:1) → `.ah-link`, `--brand-text`
- En modo oscuro los textos rojos usan `#FF6B6F`

Si ves `color: var(--brand)` en algún texto, es un defecto.

Y una heurística: **si en una pantalla el rojo aparece más de tres veces, algo está mal jerarquizado.** La estructura la da el borde de 1px, no el color.

## Convenciones

- Documentación y comentarios en español; código y base de datos en inglés.
- **Ninguna cadena visible dentro de una vista.** Todo pasa por `lang/`. Un cambio que rompa esto no entra: retrofitear i18n después salió carísimo en qualion (305 vistas, ~3,000 cadenas).
- Los controladores son delgados: solo HTTP. La lógica de negocio vive en `app/Domain/`.
- Cada lista necesita estado vacío, de carga y de error. Ninguna pantalla se queda en blanco.

## Pendiente en esta fase

| | Qué falta | Bloqueado por |
|---|---|---|
| 1.7 | Logo vectorial con transparencia | El actual es un JPEG de 200×189 renombrado a `.png`. Pendiente P10 |
| 1.8 | Despliegue en staging con HTTPS | **No existe ningún nombre DNS** que apunte al servidor. Let's Encrypt no emite para IP cruda |
| 1.9 | Preparar el servidor: `intl`, `gd`, `bcmath`, `node`, `supervisor`, swap, y mover el pool de FPM de 8.1 a **8.3** | `sudo` pide contraseña en el servidor |

## Trampas que ya nos costaron tiempo

- **No escribas rutas con comodín dentro de un comentario de bloque PHP.** `lang/*/nav.php` dentro de un `/* */` cierra el comentario en el `*/` y tumba el archivo entero.
- **`@json()` de Blade no soporta arrays anidados multilínea.** Ármalos en un bloque `@php` y pasa la variable.
