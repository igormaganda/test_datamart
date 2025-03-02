<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamIdToUsersTable extends Migration
{
    /**
     * Exécutez la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajout de la colonne 'team_id' et la définition de la relation avec la table 'teams'
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
        });
    }

    /**
     * Annulez la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer la contrainte et la colonne 'team_id'
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });
    }
}
