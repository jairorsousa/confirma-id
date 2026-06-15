<?php

namespace App\Filament\Resources\Activities\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_name')
                    ->label('Log')
                    ->badge()
                    ->searchable(),
                TextColumn::make('event')
                    ->label('Evento')
                    ->badge()
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Descricao')
                    ->searchable(),
                TextColumn::make('causer.name')
                    ->label('Responsavel')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label('Entidade')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-')
                    ->searchable(),
                TextColumn::make('subject_id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Registrado em')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->label('Evento')
                    ->options([
                        'approved' => 'Aprovacao',
                        'rejected' => 'Reprovacao',
                        'correction_requested' => 'Correcao solicitada',
                        'blocked' => 'Bloqueio',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
            ]);
    }
}
