<?php

return array (
  'people' => 
  array (
    'title' => 'Personas',
    'subtitle' => ':count personas · :locations sedes',
    'new' => 'Nuevo empleado',
    'edit' => 'Editar empleado',
    'search' => 'Buscar por nombre, extensión o correo…',
    'created' => 'Empleado dado de alta. Ya aparece en el directorio.',
    'updated' => 'Cambios guardados.',
    'deactivated' => ':name quedó dado de baja. Su historial se conserva.',
    'restored' => ':name fue reactivado.',
    'confirm_off' => '¿Dar de baja a esta persona? Sale del directorio pero su historial se conserva.',
    'none_title' => 'No encontramos a nadie',
    'none_body' => 'Prueba con otro nombre, o cambia el filtro de sede.',
  ),
  'fields' => 
  array (
    'first_name' => 'Nombre',
    'last_name' => 'Apellido',
    'preferred_name' => 'Apodo',
    'email' => 'Correo',
    'extension' => 'Extensión',
    'phone' => 'Teléfono',
    'job_title' => 'Puesto',
    'job_title_es' => 'Puesto (español)',
    'job_title_en' => 'Puesto (inglés)',
    'department' => 'Departamento',
    'location' => 'Sede',
    'manager' => 'Jefe directo',
    'birthday' => 'Cumpleaños',
    'birth_month' => 'Mes',
    'birth_day' => 'Día',
    'hire_date' => 'Fecha de ingreso',
    'status' => 'Estado',
    'photo' => 'Foto',
  ),
  'filters' => 
  array (
    'all_locations' => 'Todas las sedes',
    'all_departments' => 'Todos los departamentos',
    'active' => 'Activos',
    'inactive' => 'Dados de baja',
    'all' => 'Todos',
  ),
  'status' => 
  array (
    'active' => 'Activo',
    'inactive' => 'Baja',
  ),
  'actions' => 
  array (
    'save' => 'Guardar',
    'cancel' => 'Cancelar',
    'edit' => 'Editar',
    'deactivate' => 'Dar de baja',
    'restore' => 'Reactivar',
  ),
  'validation' => 
  array (
    'required' => 'Este campo es obligatorio.',
    'email_taken' => 'Ya existe otra persona con este correo.',
    'email_format' => 'El correo no tiene un formato válido.',
  ),
  'warnings' => 
  array (
    'title' => 'Pendientes de limpieza de datos',
    'sin_cumpleanos' => ':count personas sin fecha de cumpleaños',
    'sin_departamento' => ':count sin departamento',
    'sin_sede' => ':count sin sede',
    'hint' => 'Vienen de la importación del directorio viejo. Se corrigen editando a cada persona.',
  ),
  'hints' => 
  array (
    'no_year' => 'Solo mes y día. No guardamos el año de nacimiento.',
    'optional' => 'Opcional',
  ),
);
