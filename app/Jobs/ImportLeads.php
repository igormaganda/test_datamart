<?php

namespace App\Jobs;

use App\Imports\LeadsImport; // Assurez-vous que cette classe est correctement importée
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel; // Import de la façade Excel
use Exception;

class ImportLeads implements ShouldQueue
{
    use Queueable;
    
    protected $file;

    /**
     * Create a new job instance.
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Logique d'importation
            Excel::import(new LeadsImport, $this->file);

            // Notification de succès
            Notification::make()
                ->title('Importation réussie')
                ->success()
                ->send();
        } catch (Exception $e) {
            // Notification d'erreur avec le message d'exception
            Notification::make()
                ->title('Erreur lors de l\'importation')
                ->danger()
                ->body($e->getMessage()) // Ajoute le message d'erreur spécifique
                ->send();
        }
    }
}
