<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
                Select::make('account_status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        'blocked' => 'Bloqueado',
                    ])
                    ->required()
                    ->default('active'),
                Select::make('roles')
                    ->label('Perfis')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
            ]);
    }
}
