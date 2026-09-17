<?php

return [
    'app_name' => 'Ariel Hub',

    'greeting' => [
        'morning'   => 'Buenos días, :name',
        'afternoon' => 'Buenas tardes, :name',
        'evening'   => 'Buenas noches, :name',
    ],

    'home' => [
        'today'            => 'Hoy en Ariel',
        'birthdays_today'  => 'Cumpleaños de hoy',
        'no_birthdays'     => 'Hoy no cumple años nadie',
        'my_day'           => 'Mi día',
        'nothing_today'    => 'No tienes nada agendado hoy',
        'latest'           => 'Último',
        'quick_access'     => 'Accesos',
        'upcoming'         => 'Próximo',
        'congratulate'     => 'Felicitar',
        'see_all'          => 'Ver todo',
        'open'             => 'Abrir',
        'latest_bulletin'  => 'Último boletín',
    ],

    'stats' => [
        'people'         => ':count personas',
        'rooms_free'     => ':count libres ahora',
        'documents'      => ':count documentos',
        'my_requests'    => ':count mías',
        'apps'           => ':count aplicaciones',
    ],

    'states' => [
        'empty_title'   => 'Todavía no hay nada aquí',
        'empty_body'    => 'Cuando haya contenido, aparecerá en esta sección.',
        'loading'       => 'Cargando…',
        'error_title'   => 'No pudimos cargar esta sección',
        'error_body'    => 'El error quedó registrado. Intenta de nuevo en un momento.',
        'no_results'    => 'No encontramos nada con «:term»',
        'no_results_hint' => '¿Buscabas en otra sede o con otro apellido?',
    ],

    'search' => [
        'placeholder'  => 'Buscar personas, documentos, enlaces…',
        'shortcut_hint'=> 'para buscar',
        'navigate'     => 'navegar',
        'select'       => 'abrir',
        'close'        => 'cerrar',
        'people'       => 'Personas',
        'documents'    => 'Documentos',
        'links'        => 'Enlaces',
        'actions'      => 'Acciones',
    ],

    'placeholder' => [
        'title' => 'En construcción',
        'body'  => 'Esta sección se entrega en la :phase. Por ahora existe para que puedas navegar la estructura completa.',
        'phase' => 'Fase :n',
    ],


    'directory' => [
        'subtitle'      => ':count personas · :locations sedes',
        'search'        => 'Buscar por nombre, extensión o correo…',
        'all_locations' => 'Todas las sedes',
        'all_departments' => 'Todos los departamentos',
        'view_cards'    => 'Tarjetas',
        'view_table'    => 'Tabla',
        'clear'         => 'Limpiar filtros',
        'export'        => 'Exportar CSV',
        'first_name'    => 'Nombre',
        'last_name'     => 'Apellido',
        'job_title'     => 'Puesto',
        'department'    => 'Departamento',
        'location'      => 'Sede',
        'extension'     => 'Extensión',
        'phone'         => 'Teléfono',
        'email'         => 'Correo',
        'reports_to'    => 'Reporta a',
        'team'          => 'Equipo',
        'team_count'    => 'Equipo (:count)',
        'no_team'       => 'No tiene equipo directo asignado',
        'no_manager'    => 'Sin jefe directo asignado',
        'org_chart'     => 'Ver en el organigrama',
        'back'          => 'Volver al directorio',
        'no_email'      => 'Sin correo',
        'no_extension'  => 'Sin extensión',
        'no_birthday'   => 'Sin cumpleaños registrado',
    ],

    'footer' => [
        'contact' => 'Contacto de la intranet',
    ],

    'guest' => [
        'browsing' => 'Estás navegando como invitado.',
        'sign_in_to_act' => 'Inicia sesión para :accion.',
        'act_request' => 'enviar una solicitud',
        'act_book' => 'reservar una sala',
        'act_kudo' => 'publicar un reconocimiento',
    ],
];
