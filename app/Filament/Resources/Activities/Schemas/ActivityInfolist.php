<?php

namespace App\Filament\Resources\Activities\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Atividade')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('log_name')
                                    ->label('Log')
                                    ->badge(),
                                TextEntry::make('event')
                                    ->label('Evento')
                                    ->badge(),
                                TextEntry::make('description')
                                    ->label('Descricao'),
                                TextEntry::make('causer.name')
                                    ->label('Responsavel')
                                    ->placeholder('-'),
                                TextEntry::make('subject_type')
                                    ->label('Entidade')
                                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-'),
                                TextEntry::make('subject_id')
                                    ->label('ID'),
                                TextEntry::make('properties')
                                    ->label('Propriedades')
                                    ->formatStateUsing(fn ($state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '-')
                                    ->columnSpanFull(),
                                TextEntry::make('created_at')
                                    ->label('Registrado em')
                                    ->dateTime(),
                            ]),
                    ]),
            ]);
    }
}
