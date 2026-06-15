<?php

namespace App\Filament\Resources\Partners\Schemas;

use App\Models\Partner;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('legal_name')
                    ->label('Razao social')
                    ->required(),
                TextInput::make('trade_name')
                    ->label('Nome fantasia'),
                TextInput::make('cnpj')
                    ->label('CNPJ')
                    ->required(),
                TextInput::make('responsible_name')
                    ->label('Responsavel')
                    ->required(),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->label('Telefone')
                    ->tel()
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        Partner::STATUS_ACTIVE => 'Ativo',
                        Partner::STATUS_INACTIVE => 'Inativo',
                        Partner::STATUS_BLOCKED => 'Bloqueado',
                    ])
                    ->required()
                    ->default(Partner::STATUS_ACTIVE),
            ]);
    }
}
