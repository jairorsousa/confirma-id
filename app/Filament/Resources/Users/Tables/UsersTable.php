<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('profile.cpf')
                    ->label('CPF')
                    ->formatStateUsing(fn (?string $state): string => self::maskCpf($state))
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Perfis')
                    ->badge(),
                TextColumn::make('account_status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        'blocked' => 'Bloqueado',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'blocked' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label('E-mail verificado em')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('account_status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        'blocked' => 'Bloqueado',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function maskCpf(?string $cpf): string
    {
        $digits = preg_replace('/\D/', '', (string) $cpf);

        if (strlen($digits) !== 11) {
            return $cpf ?: '-';
        }

        return substr($digits, 0, 3).'.'.substr($digits, 3, 3).'.'.substr($digits, 6, 3).'-'.substr($digits, 9, 2);
    }
}
