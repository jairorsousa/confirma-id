<?php

namespace App\Filament\Resources\Verifications\Schemas;

use App\Models\Verification;
use App\Models\VerificationFile;
use App\Models\VerificationReview;
use App\Support\SensitiveData;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VerificationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dados cadastrais')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('user.profile.full_name')
                                    ->label('Nome completo')
                                    ->placeholder('-'),
                                TextEntry::make('user.email')
                                    ->label('E-mail')
                                    ->formatStateUsing(fn (?string $state): string => SensitiveData::email($state)),
                                TextEntry::make('user.profile.cpf')
                                    ->label('CPF')
                                    ->formatStateUsing(fn (?string $state): string => SensitiveData::cpf($state))
                                    ->placeholder('-'),
                                TextEntry::make('user.profile.birth_date')
                                    ->label('Nascimento')
                                    ->date()
                                    ->placeholder('-'),
                                TextEntry::make('user.profile.phone')
                                    ->label('Telefone')
                                    ->formatStateUsing(fn (?string $state): string => SensitiveData::phone($state))
                                    ->placeholder('-'),
                                TextEntry::make('attempt_number')
                                    ->label('Tentativa')
                                    ->numeric(),
                            ]),
                    ]),
                Section::make('Analise')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('document_type')
                                    ->label('Documento')
                                    ->formatStateUsing(fn (string $state): string => mb_strtoupper($state))
                                    ->badge(),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->formatStateUsing(fn (string $state): string => self::statusLabels()[$state] ?? $state)
                                    ->color(fn (string $state): string => match ($state) {
                                        Verification::STATUS_APPROVED => 'success',
                                        Verification::STATUS_REJECTED, Verification::STATUS_BLOCKED => 'danger',
                                        Verification::STATUS_CORRECTION_REQUESTED => 'warning',
                                        Verification::STATUS_UNDER_REVIEW => 'info',
                                        default => 'gray',
                                    }),
                                TextEntry::make('verification_code')
                                    ->label('Codigo ConfirmaID')
                                    ->placeholder('-')
                                    ->copyable(),
                                TextEntry::make('submitted_at')
                                    ->label('Enviada em')
                                    ->dateTime()
                                    ->placeholder('-'),
                                TextEntry::make('approved_at')
                                    ->label('Aprovada em')
                                    ->dateTime()
                                    ->placeholder('-'),
                                TextEntry::make('expires_at')
                                    ->label('Expira em')
                                    ->dateTime()
                                    ->placeholder('-'),
                            ]),
                    ]),
                Section::make('Arquivos')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                ImageEntry::make('front_document')
                                    ->label('Frente do documento')
                                    ->state(fn (Verification $record): ?string => self::fileUrl($record, VerificationFile::TYPE_FRONT))
                                    ->height(220),
                                ImageEntry::make('back_document')
                                    ->label('Verso do documento')
                                    ->state(fn (Verification $record): ?string => self::fileUrl($record, VerificationFile::TYPE_BACK))
                                    ->height(220),
                                ImageEntry::make('selfie_document')
                                    ->label('Selfie')
                                    ->state(fn (Verification $record): ?string => self::fileUrl($record, VerificationFile::TYPE_SELFIE))
                                    ->height(220),
                            ]),
                    ]),
                Section::make('Historico de decisoes')
                    ->schema([
                        RepeatableEntry::make('reviews')
                            ->label('')
                            ->schema([
                                Grid::make(5)
                                    ->schema([
                                        TextEntry::make('decision')
                                            ->label('Decisao')
                                            ->badge()
                                            ->formatStateUsing(fn (string $state): string => self::decisionLabels()[$state] ?? $state),
                                        TextEntry::make('admin.name')
                                            ->label('Analista'),
                                        TextEntry::make('reason')
                                            ->label('Motivo')
                                            ->placeholder('-'),
                                        TextEntry::make('notes')
                                            ->label('Notas internas')
                                            ->placeholder('-'),
                                        TextEntry::make('decided_at')
                                            ->label('Data')
                                            ->dateTime(),
                                    ]),
                            ])
                            ->contained(false),
                    ]),
            ]);
    }

    private static function fileUrl(Verification $verification, string $type): ?string
    {
        $file = $verification->files->firstWhere('file_type', $type);

        return $file ? route('admin.verification-files.show', $file) : null;
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

    /**
     * @return array<string, string>
     */
    private static function decisionLabels(): array
    {
        return [
            VerificationReview::DECISION_APPROVED => 'Aprovada',
            VerificationReview::DECISION_REJECTED => 'Reprovada',
            VerificationReview::DECISION_CORRECTION_REQUESTED => 'Correcao solicitada',
            VerificationReview::DECISION_BLOCKED => 'Bloqueada',
        ];
    }
}
