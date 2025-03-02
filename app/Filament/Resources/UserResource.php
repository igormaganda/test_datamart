<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;  // Ajout de l'importation de Spatie Role

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->dehydrated(fn ($state) => filled($state)),

                Forms\Components\Checkbox::make('is_admin')
                    ->label('Administrateur')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('is_admin')
                    ->label('Administrateur')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de création')
                    ->dateTime(),
            ])
            ->filters([ /* Ajoute tes filtres ici si nécessaire */ ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Ajoute tes relations ici si nécessaire
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // Méthode après la création de l'utilisateur pour assigner un rôle
    public static function afterCreate($user)
    {
        // Si l'utilisateur est marqué comme administrateur dans le formulaire
        if ($user->is_admin) {
            $adminRole = Role::findByName('admin'); // Récupère le rôle admin
            $user->assignRole($adminRole); // Attribue le rôle admin
        } else {
            // Optionnellement, attribuer un rôle utilisateur par défaut
            $user->assignRole('user');
        }
    }
}
