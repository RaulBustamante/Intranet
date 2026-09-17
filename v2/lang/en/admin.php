<?php

return array (
  'people' => 
  array (
    'title' => 'People',
    'subtitle' => ':count people · :locations locations',
    'new' => 'New employee',
    'edit' => 'Edit employee',
    'search' => 'Search by name, extension or email…',
    'created' => 'Employee added. They already show in the directory.',
    'updated' => 'Changes saved.',
    'deactivated' => ':name was deactivated. Their history is kept.',
    'restored' => ':name was reactivated.',
    'confirm_off' => 'Deactivate this person? They leave the directory but their history is kept.',
    'none_title' => 'Nobody found',
    'none_body' => 'Try another name, or change the location filter.',
  ),
  'fields' => 
  array (
    'first_name' => 'First name',
    'last_name' => 'Last name',
    'preferred_name' => 'Preferred name',
    'email' => 'Email',
    'extension' => 'Extension',
    'phone' => 'Phone',
    'job_title' => 'Job title',
    'job_title_es' => 'Job title (Spanish)',
    'job_title_en' => 'Job title (English)',
    'department' => 'Department',
    'location' => 'Location',
    'manager' => 'Manager',
    'birthday' => 'Birthday',
    'birth_month' => 'Month',
    'birth_day' => 'Day',
    'hire_date' => 'Hire date',
    'status' => 'Status',
    'photo' => 'Photo',
  ),
  'filters' => 
  array (
    'all_locations' => 'All locations',
    'all_departments' => 'All departments',
    'active' => 'Active',
    'inactive' => 'Deactivated',
    'all' => 'All',
  ),
  'status' => 
  array (
    'active' => 'Active',
    'inactive' => 'Inactive',
  ),
  'actions' => 
  array (
    'save' => 'Save',
    'cancel' => 'Cancel',
    'edit' => 'Edit',
    'deactivate' => 'Deactivate',
    'restore' => 'Reactivate',
  ),
  'validation' => 
  array (
    'required' => 'This field is required.',
    'email_taken' => 'Another person already uses this email.',
    'email_format' => 'That email format is not valid.',
  ),
  'warnings' => 
  array (
    'title' => 'Data clean-up pending',
    'sin_cumpleanos' => ':count people with no birthday',
    'sin_departamento' => ':count with no department',
    'sin_sede' => ':count with no location',
    'hint' => 'These come from importing the old directory. Fix them by editing each person.',
  ),
  'hints' => 
  array (
    'no_year' => 'Month and day only. We never store the birth year.',
    'optional' => 'Optional',
  ),
);
