<?php

namespace App\Http\Controllers;

use App\Domain\People\Models\Employee;
use Illuminate\View\View;

class DirectoryController extends Controller
{
    /**
     * Ficha de persona (PPL-06) con el arranque del organigrama (PPL-07).
     *
     * Carga anticipada del jefe y del equipo directo: sin eso, una ficha con
     * 8 reportes dispara 10 consultas. La v1 no tenía este problema porque
     * no tenía base de datos, pero tampoco tenía organigrama.
     */
    public function show(Employee $employee): View
    {
        $employee->load([
            'department:id,name_es,name_en',
            'location:id,code,name_es,name_en,city,country',
            'manager:id,first_name,last_name,preferred_name,photo_path,job_title_es,job_title_en,department_id',
            'manager.department:id,name_es,name_en',
            'reports' => fn ($q) => $q->active()->orderBy('first_name'),
            'reports.department:id,name_es,name_en',
        ]);

        return view('pages.directory-show', compact('employee'));
    }
}
