<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use App\Jobs\ImportLeads;  // Ajoutez l'import du job

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';


    // Formulaire de création et édition d'un lead
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nom du lead')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('phone')
                ->label('Numéro de téléphone')
                ->required()
                ->maxLength(20),

            Forms\Components\TextInput::make('company')
                ->label('Entreprise'),
                Forms\Components\FileUpload::make('import_file')
                ->label('Importer des leads')
                ->acceptedFileTypes(['application/vnd.ms-excel', 'text/csv'])
                ->required(false) // Le fichier n'est pas obligatoire, si vous voulez le rendre obligatoire, définissez-le sur true
                ->afterStateUpdated(function ($state) {
                    if ($state) {
                        // Traiter l'importation du fichier ici
                        // Par exemple, vous pouvez utiliser un job pour traiter le fichier CSV
                        ImportLeads::dispatch($state); // On passe le chemin du fichier à votre job
                    }
                }),
        ]);
    }

    // Définition des colonnes pour la table
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom du lead')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('Numéro de téléphone')
                    ->sortable(),

                TextColumn::make('company')
                    ->label('Entreprise'),

                BooleanColumn::make('is_important')
                    ->label('Lead important')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Date de création')
                    ->dateTime(),
            ])
            ->filters([/* Ajouter des filtres si nécessaire */])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Ajouter des relations si nécessaire
    public static function getRelations(): array
    {
        return [
            // Relations comme les utilisateurs, etc. peuvent être définies ici
        ];
    }

    // Définir les pages pour la ressource
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
