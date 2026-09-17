@props(['name' => 'dot', 'class' => 'w-4 h-4'])

@php
    // Iconos en línea: sin librería externa, sin petición de red, y heredan
    // currentColor para funcionar igual en claro y oscuro.
    $paths = [
        'home'     => '<path d="M3 9.5 12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5Z"/>',
        'users'    => '<circle cx="9" cy="8" r="3.2"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M17 5.5a3 3 0 0 1 0 5.8M18 14.2a5.6 5.6 0 0 1 3.5 5.3"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'door'     => '<path d="M4 21V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v17"/><path d="M2 21h20"/><circle cx="13" cy="12" r="1"/>',
        'file'     => '<path d="M14 3H7a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V7l-4-4Z"/><path d="M14 3v4h4"/>',
        'inbox'    => '<path d="M3 13h5l1.5 3h5L16 13h5"/><path d="M4.5 5h15l1.5 8v5a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-5l1.5-8Z"/>',
        'news'     => '<path d="M4 5h12a1 1 0 0 1 1 1v13H5a1 1 0 0 1-1-1V5Z"/><path d="M17 9h2a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2"/><path d="M7 9h6M7 12h6M7 15h4"/>',
        'image'    => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9.5" r="1.5"/><path d="m4 17 4.5-4.5 3 3L15 12l5 5"/>',
        'link'     => '<path d="M10.5 13.5a4 4 0 0 0 5.7 0l2.6-2.6a4 4 0 0 0-5.7-5.7l-1.3 1.3"/><path d="M13.5 10.5a4 4 0 0 0-5.7 0l-2.6 2.6a4 4 0 0 0 5.7 5.7l1.3-1.3"/>',
        'badge'    => '<path d="m12 3 2.4 1.8 3 .1.9 2.9 2.4 1.7-1 2.9 1 2.9-2.4 1.7-.9 2.9-3 .1L12 22l-2.4-1.8-3-.1-.9-2.9L3.3 15l1-2.9-1-2.9 2.4-1.7.9-2.9 3-.1L12 3Z"/><path d="m9 12 2 2 4-4"/>',
        'info'     => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5M12 8h.01"/>',
        'lifebuoy' => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3.6"/><path d="m5.7 5.7 3.8 3.8M14.5 14.5l3.8 3.8M18.3 5.7l-3.8 3.8M9.5 14.5l-3.8 3.8"/>',
        'list'     => '<path d="M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01"/>',
        'edit'     => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5Z"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.6 1.6 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.6 1.6 0 0 0-1.8-.3 1.6 1.6 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1A1.6 1.6 0 0 0 9 19.4a1.6 1.6 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.6 1.6 0 0 0 .3-1.8 1.6 1.6 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1A1.6 1.6 0 0 0 4.6 9a1.6 1.6 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.6 1.6 0 0 0 1.8.3H9a1.6 1.6 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.6 1.6 0 0 0 1 1.5 1.6 1.6 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.6 1.6 0 0 0-.3 1.8V9a1.6 1.6 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.6 1.6 0 0 0-1.5 1Z"/>',
        'shield'   => '<path d="M12 3 4.5 6v6c0 4.4 3.1 8.3 7.5 9.4 4.4-1.1 7.5-5 7.5-9.4V6L12 3Z"/><path d="m9 12 2 2 4-4"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'sun'      => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
        'moon'     => '<path d="M20 14.5A8.5 8.5 0 0 1 9.5 4a8.5 8.5 0 1 0 10.5 10.5Z"/>',
        'globe'    => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18 15 15 0 0 1 0-18Z"/>',
        'cake'     => '<path d="M4 14.5c1.2 0 1.2 1 2.4 1s1.2-1 2.4-1 1.2 1 2.4 1 1.2-1 2.4-1 1.2 1 2.4 1 1.2-1 2.4-1"/><path d="M4 14.5V20a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-5.5"/><path d="M6 14.5v-2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2"/><path d="M12 10.5V8M12 5.5v.01"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'chevron'  => '<path d="m9 6 6 6-6 6"/>',
        'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
        'arrow-right'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'dot'      => '<circle cx="12" cy="12" r="4"/>',
        'menu'     => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'x'        => '<path d="M6 6l12 12M18 6 6 18"/>',
    ];
    $d = $paths[$name] ?? $paths['dot'];
@endphp

<svg {{ $attributes->merge(['class' => $class]) }}
     viewBox="0 0 24 24" fill="none" stroke="currentColor"
     stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"
     aria-hidden="true" focusable="false">
    {!! $d !!}
</svg>
