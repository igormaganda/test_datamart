<?php

namespace App\Jobs;

use App\Imports\LeadsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportLeads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = storage_path('app/public/' . $filePath); // Corrige le chemin
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if (!file_exists($this->filePath)) {
                throw new \Exception("Le fichier CSV n'existe pas : " . $this->filePath);
            }

            Excel::import(new LeadsImport, $this->filePath); // Exécute l'import

            Log::info("Importation réussie du fichier : " . $this->filePath);
        } catch (\Exception $e) {
            Log::error('Erreur d\'importation des leads : ' . $e->getMessage());
        }
    }
}
