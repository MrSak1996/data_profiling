<?php

namespace App\Exports;

use App\Models\UnmatchedOnbintRecord;
use Maatwebsite\Excel\Concerns\FromCollection;

class OnbintExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return UnmatchedOnbintRecord::all();
    }
}
