<?php

namespace App\Filament\Imports;

use App\Models\LeadsImport;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
class LeadsImportImporter extends Importer
{
    protected static ?string $model = LeadsImport::class;

    public static function getColumns(): array
    {
        return [
            'name' => 'name',
            'email' => 'email',
            'phone_number' => 'phone_number',
        ];
    }
    

    public function resolveRecord(): ?LeadsImport
    {
        return LeadsImport::firstOrNew([
            'email' => $this->data['email'], // Match by email
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your leads import has completed, and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' were imported successfully.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
