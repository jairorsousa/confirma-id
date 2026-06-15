<?php

namespace App\Filament\Resources\Partners\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PartnerInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('legal_name'),
                TextEntry::make('trade_name')
                    ->placeholder('-'),
                TextEntry::make('cnpj'),
                TextEntry::make('responsible_name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('phone'),
                TextEntry::make('status'),
                TextEntry::make('plan_name')
                    ->label('Plano'),
                TextEntry::make('can_query_cpf')
                    ->label('Consulta CPF')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Permitida' : 'Bloqueada'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
