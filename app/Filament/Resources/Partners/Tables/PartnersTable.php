<?php

namespace App\Filament\Resources\Partners\Tables;

use App\Models\Partner;
use App\Support\SensitiveData;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('legal_name')
                    ->label('Razao social')
                    ->searchable(),
                TextColumn::make('trade_name')
                    ->label('Nome fantasia')
                    ->searchable(),
                TextColumn::make('cnpj')
                    ->label('CNPJ')
                    ->searchable(),
                TextColumn::make('responsible_name')
                    ->label('Responsavel')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->formatStateUsing(fn (?string $state): string => SensitiveData::email($state))
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Telefone')
                    ->formatStateUsing(fn (?string $state): string => SensitiveData::phone($state))
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Partner::STATUS_ACTIVE => 'Ativo',
                        Partner::STATUS_INACTIVE => 'Inativo',
                        Partner::STATUS_BLOCKED => 'Bloqueado',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        Partner::STATUS_ACTIVE => 'success',
                        Partner::STATUS_BLOCKED => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('plan_name')
                    ->label('Plano')
                    ->badge()
                    ->searchable(),
                TextColumn::make('can_query_cpf')
                    ->label('CPF')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Permitido' : 'Bloqueado')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        Partner::STATUS_ACTIVE => 'Ativo',
                        Partner::STATUS_INACTIVE => 'Inativo',
                        Partner::STATUS_BLOCKED => 'Bloqueado',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}
