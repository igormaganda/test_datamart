<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Définir le formulaire de création/édition d'une équipe
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nom de l\'équipe')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->label('Description')
                ->maxLength(500),

            Forms\Components\Select::make('team_type')
                ->label('Type d\'équipe')
                ->options([
                    'free' => 'Gratuit',
                    'premium' => 'Premium',
                    'enterprise' => 'Entreprise',
                ])
                ->required(),
        ]);
    }

    // Définir les colonnes pour la table
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom de l\'équipe')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),

                Tables\Columns\TextColumn::make('team_type')
                    ->label('Type d\'équipe')
                    ->enum([
                        'free' => 'Gratuit',
                        'premium' => 'Premium',
                        'enterprise' => 'Entreprise',
                    ]),
            ])
            ->filters([
                // Ajouter des filtres si nécessaire
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Définir les relations si nécessaire
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
