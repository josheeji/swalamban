<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LeadExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($accountTypes)
    {
        $this->accountTypes = $accountTypes;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('admin.export.leadExport', ['accountTypes' => $this->accountTypes]);
    }
}
