<?php

namespace App\Filament\Resources\PartnerQueries\Tables;

use App\Models\PartnerQuery;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnerQueriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('partner.id')
                    ->label('Parceiro')
                    ->formatStateUsing(fn (PartnerQuery $record): string => $record->partner?->trade_name ?: $record->partner?->legal_name ?: '#'.$record->partner_id)
                    ->searchable(['partners.legal_name', 'partners.trade_name']),
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('query_type')
                    ->label('Tipo')
                    ->badge()
                    ->searchable(),
                TextColumn::make('queried_term_masked')
                    ->label('Termo')
                    ->searchable(),
                TextColumn::make('result')
                    ->label('Resultado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        PartnerQuery::RESULT_APPROVED => 'Aprovado',
                        PartnerQuery::RESULT_UNDER_REVIEW => 'Em analise',
                        PartnerQuery::RESULT_NOT_FOUND => 'Nao encontrado',
                        PartnerQuery::RESULT_BLOCKED => 'Bloqueado',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        PartnerQuery::RESULT_APPROVED => 'success',
                        PartnerQuery::RESULT_BLOCKED => 'danger',
                        PartnerQuery::RESULT_UNDER_REVIEW => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable(),
                TextColumn::make('origin')
                    ->label('Origem')
                    ->searchable(),
                TextColumn::make('credential_label')
                    ->label('Credencial')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Consultada em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('result')
                    ->label('Resultado')
                    ->options([
                        PartnerQuery::RESULT_APPROVED => 'Aprovado',
                        PartnerQuery::RESULT_UNDER_REVIEW => 'Em analise',
                        PartnerQuery::RESULT_NOT_FOUND => 'Nao encontrado',
                        PartnerQuery::RESULT_BLOCKED => 'Bloqueado',
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
