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

                // Ajouter une option pour sélectionner le rôle de l'utilisateur (entreprise, salarié)
                Forms\Components\Select::make('role')
                    ->label('Rôle')
                    ->options([
                        'entreprise' => 'Entreprise',
                        'salarie' => 'Salarié',
                    ])
                    ->required(),
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
        // Vérifier le rôle sélectionné et assigner le rôle approprié
        if ($user->role === 'entreprise') {
            $role = Role::findByName('entreprise');
            $user->assignRole($role);

            // Si l'utilisateur est une entreprise, il peut ajouter des salariés
            // Vous pouvez mettre en place une logique pour gérer les salariés ici
        } elseif ($user->role === 'salarie') {
            $role = Role::findByName('salarie');
            $user->assignRole($role);
        } else {
            $role = Role::findByName('user');
            $user->assignRole($role);
        }

        // Assigner un rôle admin si 'is_admin' est coché
        if ($user->is_admin) {
            $adminRole = Role::findByName('admin');
            $user->assignRole($adminRole);
        }
    }
}
