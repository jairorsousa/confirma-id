<?php

namespace App\Filament\Resources\Verifications\Tables;

use App\Models\Verification;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VerificationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.profile.cpf')
                    ->label('CPF')
                    ->formatStateUsing(fn (?string $state): string => self::maskCpf($state))
                    ->searchable(),
                TextColumn::make('attempt_number')
                    ->label('Tentativa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('document_type')
                    ->label('Documento')
                    ->formatStateUsing(fn (string $state): string => mb_strtoupper($state))
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => self::statusLabels()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Verification::STATUS_APPROVED => 'success',
                        Verification::STATUS_REJECTED, Verification::STATUS_BLOCKED => 'danger',
                        Verification::STATUS_CORRECTION_REQUESTED => 'warning',
                        Verification::STATUS_UNDER_REVIEW => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('verification_code')
                    ->label('Codigo')
                    ->searchable(),
                TextColumn::make('submitted_at')
                    ->label('Enviada em')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->label('Aprovada em')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
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
                    ->options(self::statusLabels()),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
            ]);
    }

    /**
     * @return array<string, string>
     */
    private static function statusLabels(): array
    {
        return [
            Verification::STATUS_PENDING => 'Pendente',
            Verification::STATUS_UNDER_REVIEW => 'Em analise',
            Verification::STATUS_APPROVED => 'Aprovada',
            Verification::STATUS_REJECTED => 'Reprovada',
            Verification::STATUS_CORRECTION_REQUESTED => 'Correcao solicitada',
            Verification::STATUS_BLOCKED => 'Bloqueada',
        ];
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
