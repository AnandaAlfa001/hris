<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OffWorkExport implements FromView
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.offwork', [
            'data' => $this->data
        ]);
    }
}
