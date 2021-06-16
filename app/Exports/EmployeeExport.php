<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EmployeeExport implements FromView
{
    private $param;

    public function __construct(array $param)
    {
        $this->param = $param;
    }

    public function view(): View
    {
        $employee = Employee::all();

        return view('exports.employee', [
            'employee' => $employee
        ]);
    }
}
