<?php

namespace App\Imports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Lead([
            'name'    => $row['name'] ?? null,
            'email'   => $row['email'] ?? null,
            'phone'   => $row['phone'] ?? null,
            'company' => $row['company'] ?? null,
            'csv_filename' => request()->file('import_file')->getClientOriginalName() ?? null,
        ]);
    }
}
