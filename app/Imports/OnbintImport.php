<?php

namespace App\Imports;

use App\Models\OnbintModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;


class OnbintImport implements ToModel,WithBatchInserts
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
            return new OnbintModel([
            'RSBSASYSTEMGENERATEDNUMBER' => $row[0],  // Column 1
            'FIRSTNAME' => $row[1],                   // Column 2
        ]);
    }
    public function batchSize(): int
    {
        return 1000;
    }
    
}
