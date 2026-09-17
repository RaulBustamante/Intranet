<?php

/*
|--------------------------------------------------------------------------
| Integraciones de cuentas externas (calendario / disponibilidad estilo Teams)
|--------------------------------------------------------------------------
|
| Cada proveedor se ENCIENDE solo cuando sus credenciales OAuth están en el
| .env. Sin credenciales, el proveedor existe pero isEnabled() es false: la
| intranet funciona igual y la opción de vincular aparece deshabilitada con un
| aviso ("pídele a TI que lo configure"), nunca un error.
|
| Para encender Microsoft/Teams, TI registra una app en Entra ID y pone:
|   MS_CLIENT_ID, MS_CLIENT_SECRET, MS_TENANT_ID
| Para Google, un proyecto en Google Cloud con OAuth y:
|   GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET
|
| El redirect URI que se registra en Azure/Google es:  {APP_URL}/conexiones/{provider}/callback
| (debe ser HTTPS salvo en localhost — de ahí que el subdominio de la Fase 1
|  también desbloquee esto).
|
*/

return [

    'microsoft' => [
        'label'         => 'Microsoft / Teams',
        'client_id'     => env('MS_CLIENT_ID'),
        'client_secret' => env('MS_CLIENT_SECRET'),
        'tenant'        => env('MS_TENANT_ID', 'common'),
        // Permisos mínimos: identidad + leer calendario propio (disponibilidad).
        // Para escribir eventos de sala se añade Calendars.ReadWrite cuando se
        // active esa parte.
        'scopes'        => ['openid', 'profile', 'email', 'offline_access', 'User.Read', 'Calendars.Read'],
    ],

    'google' => [
        'label'         => 'Google',
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'scopes'        => [
            'openid', 'email', 'profile',
            'https://www.googleapis.com/auth/calendar.readonly',
        ],
    ],

];
