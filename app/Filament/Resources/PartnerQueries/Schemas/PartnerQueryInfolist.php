<?php

namespace App\Filament\Resources\PartnerQueries\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartnerQueryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Consulta')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('partner.legal_name')
                                    ->label('Parceiro'),
                                TextEntry::make('user.name')
                                    ->label('Usuario')
                                    ->placeholder('-'),
                                TextEntry::make('query_type')
                                    ->label('Tipo')
                                    ->badge(),
                                TextEntry::make('queried_term_masked')
                                    ->label('Termo consultado')
                                    ->placeholder('-'),
                                TextEntry::make('result')
                                    ->label('Resultado')
                                    ->badge(),
                                TextEntry::make('created_at')
                                    ->label('Consultada em')
                                    ->dateTime()
                                    ->placeholder('-'),
                                TextEntry::make('ip_address')
                                    ->label('IP')
                                    ->placeholder('-'),
                                TextEntry::make('origin')
                                    ->label('Origem')
                                    ->placeholder('-'),
                                TextEntry::make('credential_label')
                                    ->label('Credencial')
                                    ->placeholder('-'),
                            ]),
                    ]),
            ]);
    }
}
