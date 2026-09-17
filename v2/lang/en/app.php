<?php

return [
    'app_name' => 'Ariel Hub',

    'greeting' => [
        'morning'   => 'Good morning, :name',
        'afternoon' => 'Good afternoon, :name',
        'evening'   => 'Good evening, :name',
    ],

    'home' => [
        'today'            => 'Today at Ariel',
        'birthdays_today'  => 'Birthdays today',
        'no_birthdays'     => 'No birthdays today',
        'my_day'           => 'My day',
        'nothing_today'    => 'Nothing scheduled for you today',
        'latest'           => 'Latest',
        'quick_access'     => 'Quick access',
        'upcoming'         => 'Upcoming',
        'congratulate'     => 'Send wishes',
        'see_all'          => 'See all',
        'open'             => 'Open',
        'latest_bulletin'  => 'Latest bulletin',
    ],

    'stats' => [
        'people'         => ':count people',
        'rooms_free'     => ':count free now',
        'documents'      => ':count documents',
        'my_requests'    => ':count mine',
        'apps'           => ':count applications',
    ],

    'states' => [
        'empty_title'   => 'Nothing here yet',
        'empty_body'    => 'Content will show up in this section once it exists.',
        'loading'       => 'Loading…',
        'error_title'   => 'We could not load this section',
        'error_body'    => 'The error was logged. Please try again in a moment.',
        'no_results'    => 'Nothing found for “:term”',
        'no_results_hint' => 'Try another location or a different last name.',
    ],

    'search' => [
        'placeholder'  => 'Search people, documents, links…',
        'shortcut_hint'=> 'to search',
        'navigate'     => 'navigate',
        'select'       => 'open',
        'close'        => 'close',
        'people'       => 'People',
        'documents'    => 'Documents',
        'links'        => 'Links',
        'actions'      => 'Actions',
    ],

    'placeholder' => [
        'title' => 'Under construction',
        'body'  => 'This section ships in :phase. For now it exists so you can navigate the full structure.',
        'phase' => 'Phase :n',
    ],


    'directory' => [
        'subtitle'      => ':count people · :locations locations',
        'search'        => 'Search by name, extension or email…',
        'all_locations' => 'All locations',
        'all_departments' => 'All departments',
        'view_cards'    => 'Cards',
        'view_table'    => 'Table',
        'clear'         => 'Clear filters',
        'export'        => 'Export CSV',
        'first_name'    => 'First name',
        'last_name'     => 'Last name',
        'job_title'     => 'Job title',
        'department'    => 'Department',
        'location'      => 'Location',
        'extension'     => 'Extension',
        'phone'         => 'Phone',
        'email'         => 'Email',
        'reports_to'    => 'Reports to',
        'team'          => 'Team',
        'team_count'    => 'Team (:count)',
        'no_team'       => 'No direct reports assigned',
        'no_manager'    => 'No manager assigned',
        'org_chart'     => 'View in org chart',
        'back'          => 'Back to directory',
        'no_email'      => 'No email',
        'no_extension'  => 'No extension',
        'no_birthday'   => 'No birthday on file',
    ],

    'footer' => [
        'contact' => 'Intranet contact',
    ],

    'guest' => [
        'browsing' => 'You are browsing as a guest.',
        'sign_in_to_act' => 'Sign in to :accion.',
        'act_request' => 'submit a request',
        'act_book' => 'book a room',
        'act_kudo' => 'post a kudo',
    ],
];
