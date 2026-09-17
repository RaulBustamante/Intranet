<?php

/*
|--------------------------------------------------------------------------
| Navegación — definición ÚNICA (UX-01)
|--------------------------------------------------------------------------
|
| En la v1 las 23 vistas repetían el menú a mano y ya estaban desincronizadas:
| "Cumpleaños" en una página y "Cumpleañeros" en otra, Calendario ausente en
| varias, Directorio faltando en el menú de Directorio. Aquí se define una vez
| y <x-nav> lo consume. Cambiar una entrada la cambia en todas las páginas.
|
| Campos:
|   key    — identificador estable; es la clave de traduccion en los archivos nav.php de lang/
|   route  — nombre de ruta de Laravel, o 'url' para un enlace externo
|   icon   — nombre del icono en <x-icon>
|   role   — null = visible para cualquiera autenticado; o el rol requerido
|   group  — 'main' aparece en la barra; 'more' en el menú desplegable
|
*/

return [

    'main' => [
        ['key' => 'home',      'route' => 'dashboard',      'icon' => 'home',     'role' => null],
        ['key' => 'directory', 'route' => 'directory', 'icon' => 'users',    'role' => null],
        ['key' => 'calendar',  'route' => 'calendar',  'icon' => 'calendar', 'role' => null],
        ['key' => 'rooms',     'route' => 'rooms',     'icon' => 'door',     'role' => null],
        ['key' => 'documents', 'route' => 'documents', 'icon' => 'file',     'role' => null],
        ['key' => 'requests',  'route' => 'requests',  'icon' => 'inbox',    'role' => null],
    ],

    'more' => [
        ['key' => 'bulletins', 'route' => 'bulletins', 'icon' => 'news',    'role' => null],
        ['key' => 'gallery',   'route' => 'gallery',   'icon' => 'image',   'role' => null],
        ['key' => 'links',     'route' => 'links',     'icon' => 'link',    'role' => null],
        ['key' => 'kudos',     'route' => 'kudos',     'icon' => 'badge',   'role' => null],
        ['key' => 'iso',       'route' => 'iso',       'icon' => 'badge',   'role' => null],
        ['key' => 'about',     'route' => 'about',     'icon' => 'info',    'role' => null],
    ],

    // Zona restringida. No se dibuja para quien no tenga el rol (HRADM-06):
    // no basta con esconder el enlace, la ruta también va protegida por policy.
    'admin' => [
        ['key' => 'admin_people',   'route' => 'admin.people',   'icon' => 'users',    'role' => 'hr_editor'],
        ['key' => 'admin_catalogs', 'route' => 'admin.catalogs', 'icon' => 'list',     'role' => 'hr_editor'],
        ['key' => 'admin_content',  'route' => 'admin.content',  'icon' => 'edit',     'role' => 'content_editor'],
        ['key' => 'admin_events',   'route' => 'admin.events',   'icon' => 'calendar', 'role' => 'content_editor'],
        ['key' => 'admin_shortcuts', 'route' => 'admin.shortcuts', 'icon' => 'link',    'role' => 'content_editor'],
        ['key' => 'admin_settings', 'route' => 'admin.settings', 'icon' => 'settings', 'role' => 'admin'],
        ['key' => 'admin_audit',    'route' => 'admin.audit',    'icon' => 'shield',   'role' => 'admin'],
    ],

    // Enlaces a sistemas externos. Se mueven a base de datos en la Fase 4
    // (tabla `links`); aquí quedan solo los de acceso permanente.
    'external' => [
        ['key' => 'helpdesk', 'url' => 'https://helpme.arielapps.net/open.php', 'icon' => 'lifebuoy'],
    ],

];
