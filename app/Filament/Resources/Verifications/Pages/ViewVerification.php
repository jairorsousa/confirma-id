<?php

namespace App\Filament\Resources\Verifications\Pages;

use App\Actions\Admin\ReviewVerification;
use App\Filament\Resources\Verifications\VerificationResource;
use App\Models\Verification;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewVerification extends ViewRecord
{
    protected static string $resource = VerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Aprovar')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->form([
                    Textarea::make('notes')
                        ->label('Notas internas')
                        ->rows(3),
                ])
                ->visible(fn (): bool => $this->record->status === Verification::STATUS_UNDER_REVIEW)
                ->action(function (array $data): void {
                    app(ReviewVerification::class)->approve($this->record, auth()->user(), $data['notes'] ?? null);
                    $this->record->refresh();
                })
                ->successNotificationTitle('Verificacao aprovada'),
            Action::make('reject')
                ->label('Reprovar')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->form(self::decisionForm())
                ->visible(fn (): bool => $this->record->status === Verification::STATUS_UNDER_REVIEW)
                ->action(function (array $data): void {
                    app(ReviewVerification::class)->reject($this->record, auth()->user(), $data['reason'], $data['notes'] ?? null);
                    $this->record->refresh();
                })
                ->successNotificationTitle('Verificacao reprovada'),
            Action::make('requestCorrection')
                ->label('Solicitar correcao')
                ->color('warning')
                ->icon('heroicon-o-arrow-path')
                ->form(self::decisionForm())
                ->visible(fn (): bool => $this->record->status === Verification::STATUS_UNDER_REVIEW)
                ->action(function (array $data): void {
                    app(ReviewVerification::class)->requestCorrection($this->record, auth()->user(), $data['reason'], $data['notes'] ?? null);
                    $this->record->refresh();
                })
                ->successNotificationTitle('Correcao solicitada'),
            Action::make('block')
                ->label('Bloquear')
                ->color('danger')
                ->icon('heroicon-o-shield-exclamation')
                ->requiresConfirmation()
                ->form(self::decisionForm())
                ->visible(fn (): bool => $this->record->status === Verification::STATUS_UNDER_REVIEW)
                ->action(function (array $data): void {
                    app(ReviewVerification::class)->block($this->record, auth()->user(), $data['reason'], $data['notes'] ?? null);
                    $this->record->refresh();
                })
                ->successNotificationTitle('Verificacao bloqueada'),
        ];
    }

    private static function decisionForm(): array
    {
        return [
            Textarea::make('reason')
                ->label('Motivo')
                ->required()
                ->rows(3),
            Textarea::make('notes')
                ->label('Notas internas')
                ->rows(3),
        ];
    }
}
